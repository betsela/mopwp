/**
 * Advanced Color Control.
 *
 */

/**
 * Import Icons
*/
import ColorIcons from './color-icons';
import ColorPicker from './color-picker';
import get from 'lodash/get';
import map from 'lodash/map';

/**
 * Internal block libraries
 */
const { __, sprintf } = wp.i18n;
const {
	Component,
} = wp.element;
const {
	Button,
	Popover,
	ColorIndicator,
	Tooltip,
	Dashicon,
} = wp.components;
const {
	withSelect,
} = wp.data;
/**
 * Build the Measure controls
 * @returns {object} Measure settings.
 */
class SinglePopColorControl extends Component {
	constructor() {
		super( ...arguments );
		this.onChangeState = this.onChangeState.bind( this );
		this.onChangeComplete = this.onChangeComplete.bind( this );
		this.state = {
			alpha: false === this.props.alpha ? false : true,
			isVisible: false,
			colors: [],
			classSat: 'first',
			currentColor: '',
			inherit: false,
			isPalette: ( this.props.value && this.props.value.startsWith( 'palette' ) ? true : false ),
		};
	}
	 render() {
		const toggleVisible = () => {
			this.setState( { isVisible: true } );
		};
		const toggleClose = () => {
			if ( this.state.isVisible === true ) {
				this.setState( { isVisible: false } );
			}
		};
		if ( this.props.reload ) {
			this.props.reloaded( true );
			this.setState( { currentColor: '', isPalette: ( this.props.value && this.props.value.startsWith( 'palette' ) ? true : false ) } );
		}
		const colorVal = ( this.state.currentColor ? this.state.currentColor : this.props.value );
		let currentColorString = ( this.state.isPalette && this.props.colors && this.props.colors[ parseInt( colorVal.slice( -1 ), 10 ) - 1 ] ? this.props.colors[ parseInt( colorVal.slice( -1 ), 10 ) - 1 ].color : colorVal );
		if ( currentColorString && currentColorString.startsWith( 'var(' ) ) {
			currentColorString = window.getComputedStyle( document.documentElement ).getPropertyValue( this.props.value.replace( 'var(', '' ).replace( ')', '' ) );
		}
		if ( '' === currentColorString ) {
			currentColorString = this.props.default;
		}
		return (
			<div className="single-pop-color">
			{ this.state.isVisible && (
				<Popover position="top left" className="kadence-pop-color-popover" onClose={ toggleClose }>
					{ this.state.classSat === 'first' && ! this.props.disableCustomColors && (
						<ColorPicker
							color={ currentColorString }
							onChange={ ( color ) => this.onChangeState( color, '' ) }
							onChangeComplete={ ( color ) => {
								this.onChangeComplete( color, '' );
								if ( this.props.onClassChange ) {
								this.props.onClassChange( '' );
								}
							} }
						/>
					) }
						{ this.state.classSat !== 'first' && ! this.props.disableCustomColors && (
							<ColorPicker
								color={ currentColorString }
								onChange={ ( color ) => this.onChangeState( color, '' ) }
								onChangeComplete={ ( color ) => {
									this.onChangeComplete( color, '' );
									if ( this.props.onClassChange ) {
										this.props.onClassChange( '' );
									}
								} }
							/>
						) }
						{ this.props.colors && (
							<div className="kadence-pop-color-palette-swatches">
								{ map( this.props.colors, ( { color, slug, name } ) => {
									const style = { color };
									const palette = slug.replace( 'theme-', '' );
									const isActive = ( ( palette === this.props.value ) || ( ! slug.startsWith( 'theme-palette' ) && this.props.value === color ) );
									return (
										<div key={ color } className="kadence-color-palette__item-wrapper">
											<Tooltip
												text={ name ||
													// translators: %s: color hex code e.g: "#f00".
													sprintf( __( 'Color code: %s' ), color )
												}>
												<Button
													type="button"
													className={ `kadence-color-palette__item ${ ( isActive ? 'is-active' : '' ) }` }
													style={ style }
													onClick={ () => {
														if ( slug.startsWith( 'theme-palette' ) ) {
															this.onChangeComplete( color, palette );
														} else {
															this.onChangeComplete( color, false );
														}
														if ( this.props.onClassChange ) {
															this.props.onClassChange( slug );
														}
														// if ( 'first' === this.state.classSat ) {
														// 	this.setState( { classSat: 'second' } );
														// } else {
														// 	this.setState( { classSat: 'first' } );
														// }
													} }
													aria-label={ name ?
														// translators: %s: The name of the color e.g: "vivid red".
														sprintf( __( 'Color: %s' ), name ) :
														// translators: %s: color hex code e.g: "#f00".
														sprintf( __( 'Color code: %s' ), color ) }
													aria-pressed={ isActive }
												/>
											</Tooltip>
											{ palette === this.props.value && <Dashicon icon="admin-site" /> }
											{ ! slug.startsWith( 'theme-palette' ) && this.props.value === color && <Dashicon icon="saved" /> }
										</div>
									);
								} ) }
							</div>
						) }
					</Popover>
				) }
				{ this.state.isVisible && (
					<Button
						className={ `kadence-pop-color-icon-indicate ${ ( this.state.alpha ? 'kadence-has-alpha' : 'kadence-no-alpha' ) }` }
						onClick={ toggleVisible }
						showTooltip={ true }
						label={ this.props.label }
						>
						<ColorIndicator className="kadence-pop-color-indicate" colorValue={ currentColorString } />
						{ '' === currentColorString && this.state.inherit && (
							<span className="color-indicator-icon">{ ColorIcons.inherit }</span>
						) }
						{ ( this.props.value && this.props.value.startsWith( 'palette' )  ) && (
							<span className="color-indicator-icon">{ <Dashicon icon="admin-site" /> }</span>
						) }
					</Button>
				) }
				{ ! this.state.isVisible && (
					<Button
						className={ `kadence-pop-color-icon-indicate ${ ( this.state.alpha ? 'kadence-has-alpha' : 'kadence-no-alpha' ) }` }
						onClick={ toggleVisible }
						showTooltip={ true }
						label={ this.props.label }
						>
						<ColorIndicator className="kadence-pop-color-indicate" colorValue={ currentColorString } />
						{ '' === currentColorString && this.state.inherit && (
							<span className="color-indicator-icon">{ ColorIcons.inherit }</span>
						) }
						{ ( this.props.value && this.props.value.startsWith( 'palette' )  ) && (
							<span className="color-indicator-icon">{ <Dashicon icon="admin-site" /> }</span>
						) }
					</Button>
				) }
			</div>
		 );
	 }
	onChangeState( color, palette ) {
		let newColor;
		if ( palette ) {
			newColor = palette;
		} else if ( undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a ) {
			newColor = 'rgba(' + color.rgb.r + ',' + color.rgb.g + ',' + color.rgb.b + ',' + color.rgb.a + ')';
		} else if ( undefined !== color.hex ) {
			newColor = color.hex;
		} else {
			newColor = color;
		}
		this.setState( { currentColor: newColor, isPalette: ( palette ? true : false ) } );
		//this.props.onChange( newColor );
	}
	onChangeComplete( color, palette ) {
		let newColor;
		if ( palette ) {
			newColor = palette;
		} else if ( undefined !== color.rgb && undefined !== color.rgb.a && 1 !== color.rgb.a ) {
			newColor = 'rgba(' + color.rgb.r + ',' + color.rgb.g + ',' + color.rgb.b + ',' + color.rgb.a + ')';
		} else if ( undefined !== color.hex ) {
			newColor = color.hex;
		} else {
			newColor = color;
		}
		this.setState( { currentColor: newColor, isPalette: ( palette ? true : false ) } );
		this.props.onChange( newColor );
	}
 }
 export default withSelect( ( select, ownProps ) => {
	 const settings = select( 'core/block-editor' ).getSettings();
	 const colors = get( settings, [ 'colors' ], [] );
	 const disableCustomColors = ownProps.disableCustomColors === undefined ? settings.disableCustomColors : ownProps.disableCustomColors;
	 return {
		 colors,
		 disableCustomColors,
	 };
 } )( SinglePopColorControl );
 