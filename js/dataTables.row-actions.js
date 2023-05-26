/*
 * @copyright   Copyright (C) 2010-2023 Combodo SARL
 * @license     http://opensource.org/licenses/AGPL-3.0
 */

/**
 * Return column JSON declaration for row actions.
 * Could be part of column or columnDefs declaration of datatable.js.
 *
 * @param sTableId
 * @param iColumnTargetIndex
 * @returns {*}
 * @since 3.1.0
 */
function getRowActionsColumnDefinition(sTableId, iColumnTargetIndex = -1)
{
	let aColumn = {
		type: "html",
		orderable: false,
		render: function ( data, type, row, meta ) {
			const sHtml = '<div>' + $(`#${sTableId}_actions_buttons_template`).html() + '</div>';
			const $Template = $(sHtml);
			initButtons($Template, row);
			return $Template.html();
		}
	};

	if (iColumnTargetIndex !== -1) {
		aColumn['targets'] = iColumnTargetIndex;
	}

	return aColumn;
}

/**
 * initButtons.
 *
 * @param $Template
 * @param data
 */
function initButtons($Template, data)
{
	// Iterate throw buttons...
	$('button', $Template).each(function(){

		const sTooltipRowData = $(this).data('tooltip-row-data');

		if(sTooltipRowData !== undefined){

			let sTooltipContent = $(this).data('tooltip-content');

			sTooltipContent = sTooltipContent.replaceAll('{item}', data[sTooltipRowData] === undefined ? '' : data[sTooltipRowData]);
			$(this).attr('data-tooltip-content', sTooltipContent);
		}

	});
}