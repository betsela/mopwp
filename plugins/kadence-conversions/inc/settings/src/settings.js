/* Global kadenceSettingsParams kadenceSettingsOptions */
/**
 * WordPress dependencies
 */
import SettingsField from './field';
import kadenceTryParseJSON from './components/common/try-parse';
const { __, sprintf } = wp.i18n;
import { dispatch } from '@wordpress/data';
import { hasFilter } from '@wordpress/hooks';
import { Fragment, Component, RawHTML, render } from '@wordpress/element';
const { TabPanel, Panel, PanelBody, PanelRow, Button, Spinner } = wp.components;
class SettingsPanel extends Component {
	constructor() {
		super( ...arguments );
		this.onChange = this.onChange.bind( this );
		this.requiredVisibleCheck = this.requiredVisibleCheck.bind( this );
		this.state = {
			update: false,
		}
	}
	onChange( setting_id, value ) {
		const newSettings = kadenceTryParseJSON( window.kadenceSettingsOptions );
		newSettings[ setting_id ] = value;
		window.kadenceSettingsOptions = JSON.stringify( newSettings );
		this.setState({ update: ! this.state.update });
	}
	requiredVisibleCheck( field ) {
		const settings = kadenceTryParseJSON( window.kadenceSettingsOptions );
		if ( ! field.required ) {
			return true;
		}
		if ( undefined === field.required[0] || undefined === field.required[1] || undefined === field.required[2] ) {
			return true;
		}
		const setting = field.required[0];
		const condition = field.required[1];
		let value = field.required[2];
		if ( 'true' === value ) {
			value = true;
		} else if ( 'false' === value ) {
			value = false;
		}
		switch (condition) {
			case '!=':
				if ( settings[ setting ] !== value ) {
					return true;
				} else {
					return false;
				}
				break;
		
			default:
				if ( settings[ setting ] == value ) {
					return true;
				} else {
					return false;
				}
				break;
		}
	}
	render() {
		const { section } = this.props;
		const control = this;
		const settings = kadenceTryParseJSON( window.kadenceSettingsOptions );
		return (
			 <Fragment>
				<h2>{ section.long_title ? section.long_title : section.title }</h2>
				{ Object.keys( section.fields ).map( function( key, index ) {
					return (
						<Fragment>
							{ control.requiredVisibleCheck( section.fields[ key ] ) ? 
								<SettingsField field={ section.fields[ key ] } fieldValue={ ( undefined !== settings[ section.fields[ key ].id ] ? settings[ section.fields[ key ].id ] : '' ) } onChange={ ( value ) => control.onChange( section.fields[ key ].id, value ) } />
							:
								''
							}
						</Fragment>
					);
				} ) }
				<Button
					className="kadence-settings-save"
					isPrimary
					onClick={ () => this.props.saveSettings() }
				>
					{ __( 'Save', 'kadence-settings' ) }
				</Button>
			 </Fragment>
		 );
	 }
 }

 export default SettingsPanel;