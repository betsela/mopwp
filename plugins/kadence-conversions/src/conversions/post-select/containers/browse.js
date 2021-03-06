/* global wp */

import React from 'react';
import PropTypes from 'prop-types';

import Browse from '../components/browse';
import { fetchJson } from '../utils/fetch';

const { addQueryArgs } = wp.url;
const { postSelectEndpoint } = window.kadenceConversionsParams;

class PostSelectBrowse extends React.Component {
	constructor() {
		super( ...arguments );
		this.state = {
			posts: [],
			filters: {},
			page: 1,
			isLoading: false,
			hasPrev: false,
			hasMore: true,
		};
	}
	

	componentDidMount() {
		this.fetchPosts();
	}

	componentWillUnmount() {
		this.fetchPostAbortController && this.fetchPostAbortController.abort();
	}

	fetchPosts() {
		const { page, isLoading, filters } = this.state;
		const { postType } = this.props;
		//console.log( postSelectEndpoint );
		if ( isLoading ) {
			return;
		}

		const query = {
			...filters,
			page,
			per_page: 25,
		};

		if ( ! query.type ) {
			query.type = Array.isArray( postType ) ? postType : [ postType ];
		}
		//console.log( query );
		// Abort current pending requests.
		if ( window.AbortController ) {
			if ( this.fetchPostAbortController ) {
				this.fetchPostAbortController.abort();
			}
			this.fetchPostAbortController = new AbortController();
		}

		this.setState( { isLoading: true } );

		fetchJson( {
			path: addQueryArgs( postSelectEndpoint, query ),
			signal: this.fetchPostAbortController.signal || null,
		} ).then( ( [ posts, headers ] ) => this.setState( {
			posts,
			hasMore: page < parseInt( headers['x-wp-totalpages'], 10 ),
			hasPrev: page > 1,
			isLoading: false,
		} ) ).catch( () => {} );
	}

	render() {
		const { posts, hasPrev, hasMore, isLoading } = this.state;
		const { selection, onToggleSelected, termFilters, postType } = this.props;

		return (
			<Browse
				isLoading={ isLoading }
				posts={ posts }
				selection={ selection }
				onToggleSelected={ onToggleSelected }
				termFilters={ termFilters }
				postTypes={ Array.isArray( postType ) ? postType : [ postType ] }
				hasPrev={ hasPrev }
				hasMore={ hasMore }
				onPrevPostsPage={ () => this.prevPage() }
				onNextPostsPage={ () => this.nextPage() }
				onApplyFilters={ filters => this.applyFilters( filters ) }
			/>
		);
	}

	nextPage() {
		this.setState(
			{ page: this.state.page + 1 },
			() => this.fetchPosts()
		);
	}

	prevPage() {
		this.setState(
			{ page: this.state.page - 1 },
			() => this.fetchPosts()
		);
	}

	applyFilters( filters ) {
		this.setState( { filters }, () => this.fetchPosts() );
	}
}

PostSelectBrowse.propTypes = {
	postType: PropTypes.arrayOf( PropTypes.string ).isRequired,
	selection: PropTypes.arrayOf( PropTypes.object ).isRequired,
	onToggleSelected: PropTypes.func.isRequired,
	termFilters: PropTypes.arrayOf( PropTypes.string ),
}

export default PostSelectBrowse;
