/* Global kadenceSettingsParams */
/**
 * WordPress dependencies
 */
const { __, sprintf } = wp.i18n;
import TextRepeater from './components/repeater/text-repeater';
import TextRepeaterExpanded from './components/repeater/text-repeater-expand';
import { Fragment, Component, RawHTML, render } from '@wordpress/element';
import { TabPanel, TextControl, SelectControl, ToggleControl, Panel, PanelBody, PanelRow, Button } from '@wordpress/components';
 class SettingsField extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			value: ( this.props.fieldValue ? this.props.fieldValue : this.props.field.default ),
		};
	}
	render() {
		const { field } = this.props;
		switch ( field.type ) {
			case 'text':
				return (
					<div className={ 'kadence-settings-component-field kadence-settings-field-type-' + field.type }>
						<TextControl
							label={ field.title }
							className={ 'kadence-settings-component-' + field.id }
							value={ this.state.value }
							onChange={ ( value ) => {
								this.setState( { value: value } );
								this.props.onChange( value );
							} }
							help={ field.help ? ( field.helpLink ? <a href={ field.helpLink } target={ '_blank' }>{ field.help }</a> : field.help ) : undefined }
						/>
					</div>
				);
			case 'text_repeater':
				return (
					<div className={ 'kadence-settings-component-field kadence-settings-field-type-' + field.type }>
						<TextRepeater
							field={ field }
							value={ this.state.value }
							onChange={ ( value ) => {
								this.setState( { value: value } );
								this.props.onChange( value );
							} }
						/>
					</div>
				);
			case 'text_repeater_expanded':
				return (
					<div className={ 'kadence-settings-component-field kadence-settings-field-type-' + field.type }>
						<TextRepeaterExpanded
							field={ field }
							value={ this.state.value }
							onChange={ ( value ) => {
								this.setState( { value: value } );
								this.props.onChange( value );
							} }
						/>
					</div>
				);
			case 'select':
				const options = Object.keys( field.options ).map( function( key, index ) {
					return { value: key, label: field.options[ key ] }
				} );
				return (
					<div className={ 'kadence-settings-component-field kadence-settings-field-type-' + field.type }>
						<SelectControl
							label={ field.title }
							value={ this.state.value }
							className={ 'kadence-settings-component-' + field.id }
							options={ options }
							help={ field.help ? field.help : undefined }
							onChange={ ( value ) => {
								this.setState( { value: value } );
								this.props.onChange( value );
							} }
						/>
					</div>
				);
			case 'switch':
				return (
					<div className={ 'kadence-settings-component-field kadence-settings-field-type-' + field.type }>
						<ToggleControl
							label={ field.title ? field.title : undefined }
							className={ 'kadence-settings-component-' + field.id }
							checked={ this.state.value }
							help={ field.help ? field.help : undefined }
							onChange={ ( value ) => {
								this.setState( { value: value } );
								this.props.onChange( value );
							} }
						/>
					</div>
				);
			case 'code_info':
				return (
					<div className={ 'kadence-settings-component-field kadence-settings-field-type-' + field.type }>
						<div className={ 'components-base-control kadence-settings-text-repeater-control' }>
							{ field.title && (
								<label className="components-base-control__label">
									{ field.title }
								</label>
							) }
							{ field.content && (
								<code className="components-base-control__code">
									{ field.content }
								</code>
							) }
						</div>
					</div>
				);
			default:
				return (
					<div className={ 'kadence-settings-component-field' }>
						{ field.title }
					</div>
				);
		}
	}
}

 export default SettingsField;