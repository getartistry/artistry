<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta name="robots" content="noindex">
	<meta name="google" value="notranslate">
	<title>Customizing Type Iframe</title>

	<style>

	/* http://meyerweb.com/eric/tools/css/reset/ 
	   v2.0 | 20110126
	   License: none (public domain)
	*/

	html, body, div, span, applet, object, iframe,
	h1, h2, h3, h4, h5, h6, p, blockquote, pre,
	a, abbr, acronym, address, big, cite, code,
	del, dfn, em, img, ins, kbd, q, s, samp,
	small, strike, strong, sub, sup, tt, var,
	b, u, i, center,
	dl, dt, dd, ol, ul, li,
	fieldset, form, label, legend,
	table, caption, tbody, tfoot, thead, tr, th, td,
	article, aside, canvas, details, embed, 
	figure, figcaption, footer, header, hgroup, 
	menu, nav, output, ruby, section, summary,
	time, mark, audio, video {
		margin: 0;
		padding: 0;
		border: 0;
		font-size: 100%;
		font: inherit;
		vertical-align: baseline;
	}
	/* HTML5 display-role reset for older browsers */
	article, aside, details, figcaption, figure, 
	footer, header, hgroup, menu, nav, section {
		display: block;
	}
	body {
		line-height: 1;
	}
	ol, ul {
		list-style: none;
	}
	blockquote, q {
		quotes: none;
	}
	blockquote:before, blockquote:after,
	q:before, q:after {
		content: '';
		content: none;
	}
	table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	*:not(input):not(textarea){
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	*,*:hover,*:active,*:focus{
		outline:none;
	}

	*{
		-webkit-box-sizing:border-box;
		box-sizing:border-box;
	}

	body{
		font-family: -apple-system, BlinkMacSystemFont, 'Helvetica Neue', Helvetica, Arial, sans-serif;
	    color:#777;
	}

	.yp-new-edit-popup{
	    width:620px;
	    background-color:#FFFFFF;
	    -webkit-box-shadow: 0px 0px 4px 1px rgba(0, 0, 0, 0.14);
	    -moz-box-shadow: 0px 0px 4px 1px rgba(0, 0, 0, 0.14);
	    box-shadow: 0px 0px 4px 1px rgba(0, 0, 0, 0.14);
	    position:fixed;
	    top:50%;
	    left:50%;
	    margin-left:-310px;
	    margin-top:-240px;
	    z-index:2147483646;
	    overflow:hidden;
	    -webkit-border-radius: 4px;
	    border-radius: 4px;
		padding:30px 40px;

		line-height:1.4;
		display:block;
	}

	.yp-new-edit-popup-background{
	    width:100%;
	    height:100%;
	    background-color:#C5B9C0;
	    position:fixed;
	    top:0%;
	    left:0%;
	    z-index:2147483645;
	    opacity:0.6;
	    display:block;
	    cursor: zoom-out;
	}

	.yp-new-edit-popup h3{
		font-weight:400;
		color:#686868;
		margin-top:0px;
		margin-bottom:30px;
		padding-bottom:10px;
		text-transform:capitalize;
		text-align:center;
		font-size:18px;
		width:80%;
		margin-left:auto;
		margin-right:auto;
		border-bottom:1px solid #DDD;
	}

	.new-edit-footer{
		text-align:right;
	}

	.new-edit-btn{
		padding:10px 16px;
		font-size:13px;
		font-weight:600;

		-webkit-border-radius:2px;
		border-radius:2px;
		cursor:pointer;
		opacity:0.92;

		display:inline-block;
	}

	.new-edit-btn:hover{
		opacity:1;
	}

	.new-edit-cancel{
		background-color: #999;
		color:#FFF;
		margin-right:10px;
	}

	.new-edit-continue{
		background-color: #419BF9;
		color: #ffffff;
	}

	.customize-type-radio-section{
		padding-bottom:40px;
	}

	.customize-type-radio{
		float: left;
		width: 140px;
		text-align: center;
		position:relative;
		cursor:pointer;
		margin-left:20px;
		margin-right:20px;
	}

	.customize-type-radio p{
		color:#818181;
		font-size:11px;
		font-weight:400;
		margin:0;
		margin-top:6px;
	}

	.clearfix{
		clear:both;
	}

	.select-radio{
		border:2px solid #BCB5B9;
		width:20px;
		height:20px;
		position:absolute;
		top:0;
		left:0px;
		-webkit-border-radius:50%;
		border-radius:50%;
	}

	.type-center .select-radio{
		left:30px;
	}

	.select-radio i{
		width:10px;
		height:10px;
		margin-top:-5px;
		margin-left:-5px;
		background-color:#BCB5B9;
		position:absolute;
		top:50%;
		left:50%;
		display:none;

		-webkit-border-radius:50%;
		border-radius:50%;
	}

	.customize-type-radio:active .select-radio i,.customize-type-radio.active .select-radio i{
		display:inline-block;
	}

	.customize-type-radio.active .select-radio{
		border-color:#419BF9;
	}

	.customize-type-radio.active .select-radio i{
		background-color:#419BF9;
	}

	.customize-type-radio h4{
		margin:0px;
		font-weight:600;
		font-size: 13px;
		padding-left: 30px;
		text-align: left;
		padding-top:1px;
	}

	.customize-type-icon{
		width:64px;
		height:64px;
		background-size:100%;
		display:inline-block;

		-webkit-filter:grayscale(1);
		-moz-filter:grayscale(1);
		filter:grayscale(1);

		margin-top: 20px;
		opacity:0.9;
	}

	.customize-type-radio.active{
		color:#585858;
	}

	.customize-type-radio.active .customize-type-icon{
		-webkit-filter:grayscale(0);
		-moz-filter:grayscale(0);
		filter:grayscale(0);
	}

	.customize-single-icon{
		background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAMAAAD04JH5AAAAbFBMVEUAAABBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/mDkqRJAAAAI3RSTlMAlGyMdH7q6RUUgCMo2N1RB+8O98u+SOI+L7StpZ01G3FhWatQPHUAAAFASURBVHja7ddZTsNAEIThdrzHS8YmsZ0dmPvfkQfaSoSEQDSmGKj/BN9TtVruivw31u3lUy0G8FkNBvi8AgP8WIEBvr2CAb4pvgBYibXqTlCCAX7bAwEqQAE6FewcCNBGNwEGIGcVHBwIICcVHB0I4I4qOIEA4g4qOIMA4nYqiEAA6WdBDAJIv1XBGgS4CVIQQMpGBY8ggBSzYAUCyLVVwRMIINWogmcQQKpcBRsQQOrsFfAwgACy72YBCCCXWTD9EMA3bxq9Ci6LAz58G8EAHxPw1wGufrfYBrCXEEAAAQQQQAABwQGG3FhiBGy8sZQAAowAVxhzoe8AAQQQQAABcECdGptCP8cEEFCvjU2h7wABBBBAAAFwgCuNudDPMQEEDJmxJPQdIIAAAggggAACCCCAAAII+JeAZSOAgN8HeAEbX/kqx0eKpwAAAABJRU5ErkJggg==');
	}

	.customize-template-icon{
		background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAMAAAD04JH5AAAAgVBMVEUAAABBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/lBm/l5IzT/AAAAKnRSTlMAiPiaHb+SxCEGI/PUEazHTYUV5zlglgLiDNy4A8x4VyaljoBuKu5IPjNsL4mKAAACAUlEQVR42u3aiW6qUBCA4QFxOSCWorJv7nre/wFvAnjbpqTBcXQqnf8ByJeczEBOgM4Ck6RdBshGmqYiYwbofcgIaATcAL3GA6LAwLfQbeYaDUgV4Dvra06JBYwtwOfp/x09NoDZCJYeF+CYOrUgOkGvPvafJjqC1U7XJWfE8JEAVNAKVkwAyNqHzi9MAFAbXZcfmAAQtzvprbph/+WEAHBbwXTWf/+NKAHgp40gtfpNPzkArHEjWPhMAJhNW4HLBAA7bwSbmAkA1bwRjGImABySRjBRTICrwDQUEwAuka57V88DnPzPeU4jKNTTAMfl1/RV8AzAT5XcgBE3YPJ4QMkNqIz3rnZBhABQZk3/OmAmAAEIQAACEAASYC2SObLcowDYc43NXDMDnIEAIo2OBOBvF9g250GMoQAEIAAB3Fn4+gBlVTauyo4BsrsB1iZ/QzY+UQDs5K7Xccj6QTIQQML8UeoWwQSZsWrm8D5AqPCFv2ATCkAAAhDAAABh7KJTFIBZ7mBblsO4IRFAwnxH5O63BrLdZRB7QABMAAXAC4gBeAEu3FJID/Az6F98oAfccgZ+YSj6I+h9BplVFnt3eGMIIAABCEAAAvjbgFQBffG4PyAKDPp6/1H5uATwGoDAJE7Xmde28L3nTr8AOuLbf20M+68D8LgE8BqAwHxo3fvvH5ikZLvg+0KQAAAAAElFTkSuQmCC');
	}

	.customize-global-icon{
		background-image:url('data:image/png;base64,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');
	}

	.type-first{
		margin-left:0px;
		margin-right:30px;
	}

	.type-last{
		margin-left:30px;
		margin-right:0px;
	}

	.type-center{
		border-left: 1px dotted #E6E6E6;
		border-right: 1px dotted #E6E6E6;
		padding-left: 30px;
		padding-right: 30px;
		margin-left: 0px;
		margin-right: 0px;
		width: 200px;
	}

	.customize-type-page-section{
		width:80%;
		position:relative;
		padding-bottom:10px;
		margin-left:auto;
		margin-right:auto;
		font-size:0;
		height: 42px;
	}

	.customize-type-page-section::after {
		content: "";
		display: block;
		width: 24px;
		height: 24px;
		background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAMAAABg3Am1AAAAM1BMVEUAAAA/rUo/q0k/rUlCrUhArEdBrElFokVArUhBrUhBq0dAq0lBrUhArEhAq0lBrElBrUkhBwhKAAAAEHRSTlMASPjwNZrAC6dp7zn3QimYMeCMtQAAAIhJREFUSMft1UESwQAUBNEgCBL+/U/rlyrVYjPVCxvM+vV6hl/aVfrLbpK+apK+C+m7EN4UY3sK4SmE327+/pP+dDg6X9WF8o9C+LdiPAdPITxF9K9F9MOt1kXwvf26WIKnYMFTRE/hPEX2FM5TZE/hPAVeFPhY4E2Bz8XTm2Jub4p50ff6/bsDZ24h13D2/jwAAAAASUVORK5CYII=');
		position: absolute;
		top: 18px;
		right: -30px;
		z-index: 50;
		background-size: 16px;
		background-repeat: no-repeat;
		background-position: center;
		opacity: 0.9;
		margin-top: -12px;
	}

	.yp-new-edit-popup.invalid-url .customize-type-page-section::after{
		display:none;
	}

	.yp-new-edit-popup.invalid-url .new-edit-continue{
		opacity: 0.8;
		pointer-events: none;
	}

	.customize-type-checkbox-section label{
		font-weight: 600;
		font-size: 11px;
		cursor: pointer;
		position: relative;
		top: -6px;
		padding-left: 8px;
		left: -4px;
	}

	.customize-type-checkbox-section{
		padding-bottom:30px;
		width:80%;
		margin-left:auto;
		margin-right:auto;
		padding-top:6px;
	}

	.customize-type-select{
		border: 2px solid #BFBFBF;
		padding: 8px 10px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
		cursor: pointer;
		font-size: 12px;
		font-weight: 600;
		color: #777;

		z-index: 13;
		position: relative;
		background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAFBAMAAACKv7BmAAAAIVBMVEX///+JiYmPj4/o6OjOzs6cnJzi4uLV1dXBwcG2trawsLAieM/bAAAAIUlEQVQI12NYKCgoycAqKBjMwKwolMDAUKjCwMDA0sAAACh8AwaPt3FxAAAAAElFTkSuQmCC');
		background-repeat:no-repeat;
		background-position: 97% center;
		background-color:#FFF;

		display:inline-block;
		width:calc(100% - 42px);
		height: 36px;
		line-height: 1.5;
		overflow-y: hidden;
		overflow-x: auto;
	}

	.customize-type-select > span:not(.customize-special-url) {
	    width: 95%;
	    display: inline-block;
	    overflow: hidden;
	    white-space: nowrap;
	    text-overflow: ellipsis;
	}

	.customize-type-select:hover,.customize-type-select.active{
		border-color:#A4A4A4;
	}

	.customize-type-select.active{
		background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAFBAMAAACKv7BmAAAAIVBMVEX///+JiYmPj4/o6OjOzs6cnJzi4uLV1dXBwcG2trawsLAieM/bAAAAIUlEQVQI12Ng4HBgYGBwEmdgYFMUMmAwFRQMYJgoKCgFABjUAn9uD0q3AAAAAElFTkSuQmCC');
	}

	.customize-type-select-list{
		position: absolute;
		background-color: #f9f9f9;
		padding: 10px 0px;
		-webkit-border-radius: 0 0 3px 3px;
		border-radius: 0 0 3px 3px;
		width: calc(98% - 42px);
		-webkit-box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.6);
		box-shadow: 0px 0px 3px 0px rgba(0,0,0,0.6);
		margin-left: 1%;
		max-height: 280px;
		overflow-y: auto;
		z-index: 11;
		display:none;
		margin-top: -2px;
	}

	.customize-type-select-list ul,.customize-type-select-list li{
		margin:0;
		padding:0;
		list-style:none;
	}

	.customize-type-select-list li{
		padding:8px 12px;
		font-size:12px;
		position:relative;
	}

	.customize-type-select-list li:not(.parent-select-list):not(.active):hover{
		background-color:rgba(0,0,0,0.08);
		color: #777777;
	}

	.type-select-placeholder{
		width: 100%;
		height: 100%;
		position: absolute;
		top: 0;
		left: 0;
		background-color:rgba(0,0,0,0.01);
		z-index:9;
		display:none;
	}

	#choose-page-type{
		position: relative;
		z-index: 20;
		pointer-events:none;
	}

	.checkbox-radio{
		border:2px solid #BCB5B9;
		width:20px;
		height:20px;
		top:0;
		left:0px;
		-webkit-border-radius:3px;
		border-radius:3px;
		display:inline-block;
		cursor:pointer;
		position:relative;
	}

	.checkbox-radio i{
		width:10px;
		height:10px;
		margin-top:-5px;
		margin-left:-5px;
		background-color:#BCB5B9;
		position:absolute;
		top:50%;
		left:50%;
		display:none;

		-webkit-border-radius:3px;
		border-radius:3px;
	}

	.checkbox-radio i{
		display:inline-block;
	}

	.checkbox-radio.active{
		border-color:#419BF9;
	}

	.checkbox-radio.active i{
		background-color:#419BF9;
	}

	.customize-special-url{
		width: 36px;
		height: 35px;
		cursor: pointer;
		display: inline-block;
		z-index: 8;
		background-color: #CBCBCB;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		font-size: 10px;
		text-align: center;
		line-height: 33px;
		font-weight: 400;
		letter-spacing: 1px;
		color:#585858;
		margin-left: 6px;
		vertical-align:top;
	}

	.customize-special-url:hover,.customize-special-url.active{
		box-shadow: 0px 0px 8px 4px rgba(0,0,0,0.08) inset;
	}

	.customize-special-url.active{
		background-color:#419BF9;
		color:#FFF;
	}

	.customize-url-input{
		border: 2px solid #BFBFBF;
		padding: 8px 10px;
		-webkit-border-radius: 4px;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 600;
		color: #777;

		z-index: 13;
		position: relative;
		background-color:#FFF;

		display:none;
		width:calc(100% - 42px);
		height: 36px;
		line-height: 1.5;
	}

	.customize-url-input:focus,.customize-url-input.active{
		border-color:#A4A4A4;
	}

	.customize-type-select-list li.active{
	    background-color: rgba(65, 155, 249, 0.9);
		color: #FFFFFF;
	}

	.customize-type-select-list li.parent-select-list {
	    font-weight: 600;
	    cursor: default !important;
	    border-bottom: 1px solid rgba(0,0,0,0.1);
	}

	.customize-type-select-list li:not(.parent-select-list) {
	    padding-left: 16px;
	}

	.customize-type-radio.disabled {
	    pointer-events: none;
	    opacity: 0.8;
	}

	.yp-exit-confirm-box{
		position: fixed;
		top: 44%;
		left: 50%;
		z-index: 2147483647;
		width: 460px;
		margin-top:-67px;
		margin-left: -230px;
		padding: 30px 0px 28px;

		background: #F6F6F6;
		color:#555555;
		line-height: 1.4;

		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;

		-webkit-box-shadow:0px 0px 4px 1px rgba(0, 0, 0, 0.14);
		-moz-box-shadow:0px 0px 4px 1px rgba(0, 0, 0, 0.14);
		box-shadow:0px 0px 4px 1px rgba(0, 0, 0, 0.14);

		text-align:center;
		display:none;
		height: 134px;
	}

	.yp-exit-confirm-box h3{
		font-weight: 400;
		color: #585858;
		margin-top: 0px;
		padding-bottom: 0px;
		margin-bottom:19px;
		text-align: center;
		font-size: 19px;
		margin-left: auto;
		margin-right: auto;
	}

	.yp-exit-confirm-box .action-btn{
		padding: 7px 12px;
		font-size: 12px;
		font-weight: 600;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		border-radius: 2px;
		cursor: pointer;
		opacity: 0.92;
		display: inline-block;
		background-color: #a4a4a4;
		color: #FFF;
		margin-left: 3px;
		margin-right: 3px;
	}

	.yp-exit-confirm-box .primary-btn{
		background-color: #419BF9;
    	color: #ffffff;
	}

	.action-btn:hover{
		opacity:1;
	}

	.yp-exit-confirm-bg{
		width:100%;
	    height:100%;
	    background-color:#000;
	    position:fixed;
	    top:0%;
	    left:0%;
	    z-index:2147483646;
	    opacity:0.5;
	    display:none;
	    cursor:zoom-out;
	}

	.action-btn.disabled{
		pointer-events: none;
	    opacity: 0.7;
	}

	li.has-style:after{
		position: absolute;
		content: "Edited";
		border-radius: 3px;
		background-color: rgba(0, 0, 0, 0.08);
		right: 38px;
		top: 50%;
		margin-top: -7px;
		margin-left: -19px;
		font-size: 9px;
		width: 38px;
		height: 14px;
		text-align: center;
		line-height: 14px;
	}

	li i{
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAMAAAAolt3jAAAAilBMVEUAAABEREBHR0IhHR4gHB4kICEsKiohHB4gHBwzMjEtJSwnJSQhEhokICEmISMnHyAjISIYGBYiICEwLy0lIiIpJycuLCw5ODUgHR8eGh0iHyEiGyArKSkuLSwzMTAqJicODAwiHh84NzUhHx8rKSckISElJCQbGhouLi1BQD0lISEgHB0jHyAdGRqXy6wyAAAAKnRSTlMABQPuxlgM+vMrFxEJ+bmynpd7cm1oTD7d16alhYBsYGBeXVFCQC8vHA0ZFof4AAAAfklEQVQIHY3BRRLDMBAAwZFkZnaYcSX//3uJq5x7uvlDMo4BP8F+Zad1k6EVcPds4fuF825oOBupSFMqZ47Q5ZLHT2MecShhz8ZJ9BrsNMSRuC1JKbana2mtlAlktcgpeB+s1Bkz33NRaD2fRXrZNdcA0HxpFkoBWinNTCv1AYESCOWUhIMiAAAAAElFTkSuQmCC);
		width: 16px;
		height: 16px;
		position: absolute;
		right: 12px;
		background-repeat: no-repeat;
		background-position: center;
		cursor: pointer;
		top: 50%;
		margin-top:-8px;
		opacity:0;
		pointer-events:none;
	}

	li span{
		width: 75%;
		display: inline-block;
	}

	li.active i{
		background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAMAAAAolt3jAAAAb1BMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8v0wLRAAAAJHRSTlMABfoM7mot88VyQRcRCbq0pp5/YF1ZV93Xx5eWhXhRTks8KBxmGbzqAAAAcUlEQVQIHY3BVRbCQBBFwdtjcSUJ7m//a0RO+KeKP1Tl3fPjx620OzeYAeVe7bK0yksMpqCBumZQ2EB0cimFkJKTi/RS9rxJ10cm9VSdFCkKCqmroDlIG++jdGz4mHNlTvnMqp5O48WzMlZmvJkZX2Yv4+8HUGFXlw0AAAAASUVORK5CYII=);
	}

	li i{
		opacity:0.35;
		pointer-events:auto;
	}

	li.active i{
		opacity:0.8;
	}

	li i:hover{
		opacity:0.6;
	}

	li.active i:hover{
		opacity:1;
	}

	li.active.has-style:after{
		background-color: rgba(255, 255, 255, 0.4);
	}
	</style>

	<script>


		function typeClick(element){

			var types = document.getElementsByClassName("customize-type-radio");

			Array.prototype.forEach.call(types ,function(entry){
				entry.classList.remove("active");
			});

			element.classList.add("active");
			element.classList.add("actived-by-user");

			formChange();

		}

		function closeSelect(){

			var select = document.getElementsByClassName("customize-type-select")[0];
			var menu = document.getElementsByClassName("customize-type-select-list")[0];
			var holder = document.getElementsByClassName("type-select-placeholder")[0];

			select.classList.remove("active");
			menu.style.display = 'none';
			holder.style.display = 'none';

		}

		function pageChoose(element){

			// Get menu
			var menu = document.getElementsByClassName("customize-type-select-list")[0];
			var holder = document.getElementsByClassName("type-select-placeholder")[0];

			if(menu.style.display == 'block'){

				element.classList.remove("active");
				menu.style.display = 'none';
				holder.style.display = 'none';

			}else{

				element.classList.add("active");
				menu.style.display = 'block';
				holder.style.display = 'block';

			}

		}

		function seePageLink(element){

			// Get Page URL
			var pageURL = decodeURIComponent(element.parentElement.getAttribute("data-type-href")).trim();
			pageURL = location.protocol + "//" + pageURL;

			// Open Tab
			var newTab = window.open(pageURL, '_blank');
  			newTab.focus();
			
			// disable list click
			var list = document.querySelectorAll("li");
			for(var i = 0; i < list.length; i++){
				document.querySelectorAll("li")[i].classList.add("no-click");
			}

			return false;

		}
	

		function typeListSelect(element){

			// Disable list click on see page link
			if(element.classList.contains("no-click") == true){

				var list = document.querySelectorAll("li");

				for(var i = 0; i < list.length; i++){
					document.querySelectorAll("li")[i].classList.remove("no-click");
				}

				return false;

			}

			// Enable All
			var types = document.querySelectorAll(".customize-type-radio");

			for(var x = 0; x < types.length; x++){
			    types[x].classList.remove("disabled");
			}
			
			// Getting ID
			var id = element.getAttribute("data-id-value");

			// Disable Single on these methods
			if(id == 'search' || id == 'tag' || id == 'category' || id == 'archive' || id == 'author' || id == '404'){

				// Disable single
				document.querySelectorAll(".customize-type-radio.type-first")[0].classList.add("disabled");

				// if single were active, so active template
				if(document.querySelectorAll(".customize-type-radio.type-first.disabled.active").length > 0){

					document.querySelectorAll(".customize-type-radio.type-first.disabled.active")[0].classList.remove("active");
					document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last)")[0].classList.add("active");

				}

			}

			// Disable template on these methods
			if(id == 'home'){
				
				// Disable template
				document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last)")[0].classList.add("disabled");

				// if template were active, so active single
				if(document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last).disabled.active").length > 0){

					document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last).disabled.active")[0].classList.remove("active");
					document.querySelectorAll(".customize-type-radio.type-first")[0].classList.add("active");

				}

			}

			// Is post or page.
			if(/^\d+$/.test(id) && id != 404){

				// if template were active, so active single
				if(document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last).active:not(.actived-by-user)").length > 0){
					document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last)")[0].classList.remove("active");
					document.querySelectorAll(".customize-type-radio.type-first")[0].classList.add("active");
				}

			}

			var findActive = document.querySelectorAll(".customize-type-select-list li.active");

			if(findActive.length > 0){
				findActive[0].classList.remove("active");
			}

			element.classList.add("active");

			var select = document.getElementsByClassName("customize-type-select")[0];

			select.innerHTML = element.innerHTML;

			closeSelect();

			formChange();

		}

		function typeCheckbox(type){

			// URLs
			var checkbox = document.getElementsByClassName("customize-visitor-view")[0];
		
			// IS active
			if(checkbox.classList.contains("active")){
				checkbox.classList.remove("active");
			}else{
				checkbox.classList.add("active");
			}

			formChange();

		}

		function typeToggleURL(element){

			var urlInput = document.getElementsByClassName("customize-url-input")[0];
			var selectPage = document.getElementsByClassName("customize-type-select")[0];

			// IS active
			if(element.classList.contains("active")){
				element.classList.remove("active");
				urlInput.style.display = 'none';
				selectPage.style.display = 'inline-block';
				document.getElementsByClassName("yp-new-edit-popup")[0].classList.remove("invalid-url");
			}else{
				element.classList.add("active");
				urlInput.style.display = 'inline-block';
				selectPage.style.display = 'none';
			}

			// Set current href
			urlInput.value = decodeURIComponent(document.querySelectorAll('.customize-type-select-list li.active')[0].getAttribute("data-type-href"));

			formChange();

		}

		function customURL(element){

			if(document.querySelectorAll(".customize-special-url.active").length > 0){

				// Get URL
				pageHref = document.getElementsByClassName("customize-url-input")[0].value.trim();

				// trim http
				if(pageHref.indexOf("://") != -1 && pageHref.length > 11){
					pageHref = pageHref.split("://")[1];
					document.getElementsByClassName("customize-url-input")[0].value = pageHref;
				}
				
				// Test URL
				if(/(\b \b|\.$|^\.)/g.test(pageHref) || pageHref.indexOf(".") == -1 || pageHref.length < 4 || pageHref.indexOf(window.location.hostname) == -1){
					document.getElementsByClassName("yp-new-edit-popup")[0].classList.add("invalid-url");
				}else{
					document.getElementsByClassName("yp-new-edit-popup")[0].classList.remove("invalid-url");
				}

				// enCode
				pageHref = encodeURIComponent(pageHref);

				// Li Href
				var liHref = document.querySelectorAll('.customize-type-select-list li[data-type-href="'+pageHref+'"]');

				var single = document.querySelectorAll(".customize-type-radio.type-first")[0];
				var template = document.querySelectorAll(".customize-type-radio:not(.type-first):not(.type-last)")[0];
				var global = document.querySelectorAll(".customize-type-radio.type-last")[0];

				// find info by href
				if(liHref.length == 0){

					single.classList.add("disabled");
					single.classList.remove("active");

					template.classList.add("disabled");
					template.classList.remove("active");

					global.classList.remove("disabled");
					global.classList.add("active");

				}else{

					single.classList.remove("disabled");
					template.classList.remove("disabled");
					global.classList.remove("disabled");

					typeListSelect(liHref[0]);

				}

			}

			formChange();

		}

		function newTypeContinue(){

			// Don't ask on demo mode
			if (window.parent.document.body.classList.contains('yp-yellow-pencil-demo-mode') == false) {

				if(window.parent.document.querySelectorAll(".yp-save-btn.waiting-for-save").length > 0 && document.querySelectorAll(".new-edit-btn.only-continue-btn").length == 0){

					// Show
					document.getElementsByClassName("yp-exit-confirm-box")[0].style.display = 'block';
					document.getElementsByClassName("yp-exit-confirm-bg")[0].style.display = 'block';

					return false;

				}
			}

			var parentIframe = window.parent.document.getElementById("yp-customizing-type-frame");
			parentIframe.style.display = 'none';

			// IF no change, click only closing popup.
			if(document.querySelectorAll(".only-continue-btn").length > 0){
				window.parent.document.getElementById("yp-current-page").classList.remove("active");
				return false;
			}

			// Generating location URL
			var pageHref;
			var pageType;
			var pageID;
			var editMode = document.querySelectorAll(".customize-type-radio.active")[0].getAttribute("data-value");

			// Base
			var yp_base_uri = "<?php echo yp_get_uri(); ?>";

			// Getting Href
			if(document.querySelectorAll(".customize-special-url.active").length > 0){

				pageHref = encodeURIComponent(document.getElementsByClassName("customize-url-input")[0].value);

				// Li Href
				var liHref = document.querySelectorAll('.customize-type-select-list li[data-type-href="'+pageHref+'"]');

				// find info by href
				if(liHref.length > 0){

					pageType = liHref[0].getAttribute("data-type-value");
					pageID = liHref[0].getAttribute("data-id-value");

					// Go
					goLink(yp_base_uri, pageHref, pageID, pageType, editMode);

				// Get page details with ajax
				}else{

					// Get page link
					var pageHrefGo = decodeURIComponent(pageHref);

					// Add slash to end
                    pageHrefGo = pageHrefGo.replace(/\/?(\?|#|$)/, '/$1');

					// XMLHTTP
					var xhttp = new XMLHttpRequest();
					xhttp.open("POST", location.protocol+"//"+pageHrefGo, true);
					xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					xhttp.overrideMimeType('text/html');
					xhttp.send("yp_get_details=true");

					// When ready
					xhttp.onreadystatechange = function(){

					    // Done
					    if (this.readyState == 4) {
					    	
					        // Success
					        if(this.status == 200){

					        	var parser = new DOMParser();
								response = parser.parseFromString(this.responseText, "text/html");
					        	response = response.getElementById("yp_page_details").innerHTML;

					            // Find page informations
					            var pageID = response.split("|")[0];
					            var pageType = response.split("|")[1];

					            // Go
								goLink(yp_base_uri, pageHref, pageID, pageType, editMode);

					        }else{

					            pageType = 'general';
								pageID = 0;

								// Go
								goLink(yp_base_uri, pageHref, pageID, pageType, editMode);

					        }

					    }

					}

				}


			// get info by active select
			}else{

				var activeLi = document.querySelectorAll('.customize-type-select-list li.active')[0];
				pageHref = activeLi.getAttribute("data-type-href");
				pageType = activeLi.getAttribute("data-type-value");
				pageID = activeLi.getAttribute("data-id-value");

				// Go
				goLink(yp_base_uri, pageHref, pageID, pageType, editMode);

			}

		}

		function goLink(yp_base_uri, pageHref, pageID, pageType, editMode){

			var visitorView = false;

			// Visitor View
			if(document.querySelectorAll(".customize-visitor-view.active").length > 0){
				visitorView = true;
			}

			if(visitorView){
				visitorView = "&yp_out=true";
			}else{
				visitorView = '';
			}

			var redirectURL = yp_base_uri + "&href=" + pageHref + "&yp_page_id=" + pageID + "&yp_page_type=" + pageType + "&yp_mode=" + editMode + visitorView;

			window.parent.document.getElementById("iframe").style.display = 'none';
			window.parent.document.body.classList.remove("yp-yellow-pencil-loaded");
			window.parent.document.getElementsByClassName("loading-files")[0].innerHTML = 'Page loading..';
			window.parent.location = redirectURL;

		}

		function newTypeCancel(){

			var parentIframe = window.parent.document.getElementById("yp-customizing-type-frame");
			window.parent.document.getElementById("yp-current-page").classList.remove("active");
			parentIframe.style.display = 'none';

		}

		function get_url_params(url) {

		    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
		    var obj = {};

		    if (queryString) {

		        queryString = queryString.split('#')[0];
		        var arr = queryString.split('&');

		        for (var i = 0; i < arr.length; i++) {

		            var a = arr[i].split('=');

		            var paramNum = undefined;
		            var paramName = a[0].replace(/\[\d*\]/, function(v) {
		                paramNum = v.slice(1, -1);
		                return '';
		            });

		            var paramValue = typeof(a[1]) === 'undefined' ? true : a[1];

		            paramName = paramName.toLowerCase();
		            paramValue = paramValue.toLowerCase();

		            if (obj[paramName]) {
		                if (typeof obj[paramName] === 'string') {
		                    obj[paramName] = [obj[paramName]];
		                }
		                if (typeof paramNum === 'undefined') {
		                    obj[paramName].push(paramValue);
		                } else {
		                    obj[paramName][paramNum] = paramValue;
		                }
		            } else {
		                obj[paramName] = paramValue;
		            }
		        }
		    }

		    return obj;

		}


		function auto_fill(){

			// Finish loaded.
			window.parent.document.body.classList.add("yp-yellow-pencil");
			window.parent.document.body.classList.add("yp-yellow-pencil-loaded");
			window.parent.document.getElementById("iframe").classList.add("yp-yellow-pencil");
			window.parent.document.getElementById("iframe").classList.add("yp-yellow-pencil-loaded");

			var url_string = window.location.href;
			var url = get_url_params(url_string);

			// gettings
			var id = url.yp_page_id;
			var type = url.yp_page_type;
			var mode = url.yp_mode;
			var visitor = url.yp_out;

			// Disable current active page
			if(id != null){

				// active
				var active = document.querySelectorAll('.customize-type-select-list li.active')[0];

				// Get it
				var idTypeFilter = document.querySelectorAll('.customize-type-select-list li[data-id-value="'+id+'"][data-type-value="'+type+'"]');
				var idFilter = document.querySelectorAll('.customize-type-select-list li[data-id-value="'+id+'"]');
				var typeFilter = document.querySelectorAll('.customize-type-select-list li[data-type-value="'+type+'"]');

				// Search active page
				if(idTypeFilter.length > 0){
					
					active.classList.remove("active");
					idTypeFilter[0].classList.add("active");

				}else if(idFilter.length > 0){
					
					active.classList.remove("active");
					idFilter[0].classList.add("active");

				}else if(typeFilter.length > 0){

					active.classList.remove("active");
					typeFilter[0].classList.add("active");

				}

			// Home page active by default.
			}

			// Apply Active
			typeListSelect(document.querySelectorAll('.customize-type-select-list li.active')[0]);

			// Apply visitor view
			if(visitor != null){
				document.getElementsByClassName("customize-visitor-view")[0].classList.add("active");
			}

			// Apply mode
			if(mode != null){
				document.querySelectorAll(".customize-type-radio.active")[0].classList.remove("active");
				document.querySelectorAll(".customize-type-radio[data-value='"+mode+"']")[0].classList.add("active");
			}

		}

		// Close Iframe with ESC
		document.onkeydown = function(evt) {

		    evt = evt || window.event;
		    var isEscape = false;

		    if ("key" in evt) {
		        isEscape = (evt.key == "Escape" || evt.key == "Esc");
		    } else {
		        isEscape = (evt.keyCode == 27);
		    }

		    if (isEscape) {

		    	if(document.getElementsByClassName("yp-exit-confirm-box")[0].style.display == 'block'){
		    		actionBtn("cancel", null);
		    	}else{
		        	newTypeCancel();
		        }

		    }
		};

		function actionBtn(key, element){

			if(key == 'save'){

				// Save click
				window.parent.document.querySelectorAll(".yp-save-btn")[0].click();

					element.innerHTML = 'Saving..';
					element.classList.add("disabled");

					// Done.
	                window.savingCheckerX = setInterval(function(){

	                    if(window.parent.document.querySelectorAll(".yp-save-btn.waiting-for-save").length == 0){
	                    	clearInterval(window.savingCheckerX);

	                    	setTimeout(function(){
								element.innerHTML = 'Saved..';
	                    	}, 100);

	                    	setTimeout(function(){
	                    		newTypeContinue();
	                    	}, 600);

	                    }

					}, 200);

			}

			if(key == 'nosave'){

				// remove for no ask a confirm exit alert
				window.parent.document.querySelectorAll(".yp-save-btn.waiting-for-save")[0].classList.remove("waiting-for-save");

				element.classList.add("disabled");
				element.innerHTML = 'Loading..';

				setTimeout(function(){
					newTypeContinue();
				}, 100);

			}

			if(key == 'cancel'){

				document.getElementsByClassName("yp-exit-confirm-box")[0].style.display = "none";
				document.getElementsByClassName("yp-exit-confirm-bg")[0].style.display = "none";

			}

		}

		function formChange(){

			// URL
			var url_string = window.location.href;
			var url = get_url_params(url_string);

			// Current Settings
			var id = url.yp_page_id;
			var href = url.yp_page_href;
			var type = url.yp_page_type;
			var mode = url.yp_mode;
			if(url.yp_out == null){var visitor = false;}else{var visitor = url.yp_out;}

			// ----

			// Getting New Popup Settings
			var idPopup;
			var hrefPopup;
			var typePopup;
			var modePopup = document.querySelectorAll(".customize-type-radio.active")[0].getAttribute("data-value");
			var visitorPopup = false;

			// Getting from href
			if(document.querySelectorAll(".customize-special-url.active").length > 0){

				hrefPopup = encodeURIComponent(document.getElementsByClassName("customize-url-input")[0].value);

				// Li Href
				var listElement = document.querySelectorAll('.customize-type-select-list li[data-type-href="'+hrefPopup+'"]');

				// find info by href
				if(listElement.length > 0){
					typePopup = listElement[0].getAttribute("data-type-value");
					idPopup = listElement[0].getAttribute("data-id-value");
				}else{
					typePopup = 'general';
					idPopup = 0;
				}

			}else{

				var active = document.querySelectorAll('.customize-type-select-list li.active')[0];
				hrefPopup = active.getAttribute("data-type-href");
				typePopup = active.getAttribute("data-type-value");
				idPopup = active.getAttribute("data-id-value");

			}

			// Visitor View
			if(document.querySelectorAll(".customize-visitor-view.active").length > 0){
				visitorPopup = true;
			}

			// Filtering URL
			href = decodeURIComponent(href);
			hrefPopup = decodeURIComponent(hrefPopup);
			if (href[href.length-1] === "/"){href = href.slice(0,-1);}
    		if (hrefPopup[hrefPopup.length-1] === "/"){hrefPopup = hrefPopup.slice(0,-1);}


			if(idPopup == id && hrefPopup == href && typePopup == type && modePopup == mode && visitorPopup == visitor){

				// Continue if same
				document.querySelectorAll(".new-edit-continue")[0].innerHTML = "Continue";
				document.querySelectorAll(".new-edit-continue")[0].classList.add("only-continue-btn");

			}else{

				// Customize if not same
				document.querySelectorAll(".new-edit-continue")[0].innerHTML = "Customize";
				document.querySelectorAll(".new-edit-continue")[0].classList.remove("only-continue-btn");
				
			}

		}

	</script>
</head>
<body onload="auto_fill()">

	<div class="yp-new-edit-popup-background" onclick="newTypeCancel();"></div>

	<div class="yp-new-edit-popup">

		<div class="type-select-placeholder" onclick="closeSelect(this)"></div>

		<h3 id="choose-page-type">Select The Target page&hellip;</h3>

		<div class="customize-type-page-section">
			<input type="text" class="customize-url-input" onkeyup="customURL(this)" onchange="customURL(this)" placeholder="<?php $sample_url = get_home_url(null,'/example-page'); $sample_url = explode("://", esc_url($sample_url)); echo $sample_url[1]; ?>" />
			<div class="customize-type-select" onclick="pageChoose(this)">Select a page...</div>

			<span class="customize-special-url" onclick="typeToggleURL(this)" title="select a page by the link">www</span>

			<div class="customize-type-select-list">
				<ul>

					<?php

					$frontpage_id = get_option('page_on_front');

					// Getting tag href
					$tag_id = 0;
					$tags = get_tags(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
					if(empty($tags) == false){
						$tag_id = $tags[0];
						$tag_link = get_tag_link($tag_id);
					}else{
						$tag_link = null;
					}

					// Getting cat href
					$cat_id = 0;
					$cats = get_categories(array('orderby' => 'count', 'order' => 'DESC','number'=> 1 ));
					if(empty($cats) == false){
						$cat_id = $cats[0];
					}
					$cat_link = get_category_link($cat_id);

					// Getting archive link
					$latest_post = get_posts("post_type=post&numberposts=1");
					$latest_post_id = $latest_post[0]->ID;
					$last_post_date = get_the_date("Y",$latest_post_id);
					$archive_link = get_home_url()."/".$last_post_date;

					?>
					
					<li class="parent-select-list">General</li>

					<?php

						// check the style
						$hasHome = "";
						$hasSearch = "";
						$hasTag = "";
						$hasCategory = "";
						$hasArchive = "";
						$hasAuthor = "";
						$has404 = "";

						// get option home
						if($frontpage_id == 0 || $frontpage_id == null){
							$homeStyle = get_option("wt_home_css");
						}else{
							$homeStyle = get_post_meta($frontpage_id, '_wt_css', true);
						}

						// get option general
						$searchStyle = get_option("wt_search_css");
						$tagStyle = get_option("wt_tag_css");
						$categoryStyle = get_option("wt_category_css");
						$archiveStyle = get_option("wt_archive_css");
						$authorStyle = get_option("wt_author_css");
						$style404 = get_option("wt_404_css");

						// check option
						if(empty($homeStyle) != true && $homeStyle != "false"){
							$hasHome = " has-style";
						}

						// check option
						if(empty($searchStyle) != true && $searchStyle != "false"){
							$hasSearch = "class='has-style'";
						}

						// check option
						if(empty($tagStyle) != true && $tagStyle != "false"){
							$hasTag = "class='has-style'";
						}

						// check option
						if(empty($categoryStyle) != true && $categoryStyle != "false"){
							$hasCategory = "class='has-style'";
						}

						// check option
						if(empty($archiveStyle) != true && $archiveStyle != "false"){
							$hasArchive = "class='has-style'";
						}

						// check option
						if(empty($authorStyle) != true && $authorStyle != "false"){
							$hasAuthor = "class='has-style'";
						}

						// check option
						if(empty($style404) != true && $style404 != "false"){
							$has404 = "class='has-style'";
						}

					?>

					<?php

					if($frontpage_id == 0 || $frontpage_id == null){ ?>
						<li onclick="typeListSelect(this)" class="active<?php echo $hasHome; ?>" data-id-value="home" data-type-value="home" data-type-href="<?php echo yp_urlencode(esc_url(get_home_url())); ?>"><i onclick="seePageLink(this)"></i><span>Homepage</span></li>
					<?php }else{ ?>
						<li onclick="typeListSelect(this)" class="active<?php echo $hasHome; ?>" data-id-value="<?php echo $frontpage_id; ?>" data-type-value="<?php echo get_post_type($frontpage_id); ?>" data-type-href="<?php echo yp_urlencode(esc_url(get_the_permalink($frontpage_id))); ?>"><i onclick="seePageLink(this)"></i><span>Homepage</span></li>
					<?php } ?>
			        <li onclick="typeListSelect(this)" data-id-value="search" <?php echo $hasSearch; ?> data-type-value="search" data-type-href="<?php echo yp_urlencode(esc_url(get_home_url().'/?s='.yp_getting_last_post_title())); ?>"><i onclick="seePageLink(this)"></i><span>Search page</span></li>
			        <?php if($tag_link != null){ ?><li onclick="typeListSelect(this)" <?php echo $hasTag; ?> data-id-value="tag" data-type-value="tag" data-type-href="<?php echo yp_urlencode(esc_url($tag_link)); ?>"><i onclick="seePageLink(this)"></i><span>Tag page</span></li><?php } ?>
			        <li onclick="typeListSelect(this)" data-id-value="category" <?php echo $hasCategory; ?> data-type-value="category" data-type-href="<?php echo yp_urlencode(esc_url($cat_link)); ?>"><i onclick="seePageLink(this)"></i><span>Category page</span></li>
			        <li onclick="typeListSelect(this)" data-id-value="archive" <?php echo $hasArchive; ?> data-type-value="archive" data-type-href="<?php echo yp_urlencode(esc_url($archive_link)); ?>"><i onclick="seePageLink(this)"></i><span>Archive page</span></li>
			        <li onclick="typeListSelect(this)" data-id-value="author" <?php echo $hasAuthor; ?> data-type-value="author" data-type-href="<?php echo yp_urlencode(esc_url(get_author_posts_url(1))); ?>"><i onclick="seePageLink(this)"></i><span>Author page</span></li>
			        <li onclick="typeListSelect(this)" data-id-value="404" <?php echo $has404; ?> data-type-value="404" data-type-href="<?php echo yp_urlencode(esc_url(get_home_url().'/?p=987654321')); ?>"><i onclick="seePageLink(this)"></i><span>404 error page</span></li>

					<?php

						// get only visible post types
						$visiblePostTypes = get_post_types(array("public" => true), 'names');

						// Each post types
						foreach ($visiblePostTypes as $post_type){

							// query for your post type
							$query = new WP_Query(  
							    array(  
							        'post_type'      => $post_type,  
							        'posts_per_page' => -1  
							    )  
							);

							if ( $query->have_posts() ) {

								echo '<li class="parent-select-list">'.ucfirst($post_type).'</li>';

								while ( $query->have_posts() ) {

									$query->the_post();
									
									$title = get_the_title();

									$id = get_the_id();

									// check the style
									$hasStyle = "";

									// with post meta
									$get_post_meta = get_post_meta($id, '_wt_css', true);

									// check post meta
									if(empty($get_post_meta) != true && $get_post_meta != "false"){
										$hasStyle = "class='has-style'";
									}

									echo '<li onclick="typeListSelect(this)" '.$hasStyle.' data-id-value="'.$id.'" data-type-value="'.$post_type.'" data-type-href="'.yp_urlencode(esc_url(get_the_permalink())).'"><i onclick="seePageLink(this)"></i><span>' . ucfirst(strtolower(yp_get_short_title($title, 50))) . '</span></li>';
								
								}

							}

						}

					?>

				</ul>
			</div>
		</div>

		<div class="customize-type-checkbox-section">				
			<span class="checkbox-radio customize-visitor-view" onclick="typeCheckbox('visitor')" title="View as visitor"><i></i></span>
			<label class="customize-visitor-view" onclick="typeCheckbox('visitor')" title="View as visitor">Visitor View</label>
		</div>

		<h3>Customizing Type</h3>

		<div class="customize-type-radio-section">

			<div class="customize-type-radio type-first active" data-value="single" onclick="typeClick(this)">
				<span class="select-radio"><i></i></span><h4>Single</h4>
				<span class="customize-type-icon customize-single-icon"></span>
				<p>apply style just to the current page.</p>
			</div>


			<div class="customize-type-radio type-center" data-value="template" onclick="typeClick(this)">
				<span class="select-radio"><i></i></span><h4>Template</h4>
				<span class="customize-type-icon customize-template-icon"></span>
				<p>apply style to all pages of the current post type.</p>
			</div>


			<div class="customize-type-radio type-last" data-value="global" onclick="typeClick(this)">
				<span class="select-radio"><i></i></span><h4>Global</h4>
				<span class="customize-type-icon customize-global-icon"></span>
				<p>apply style to the entire website.</p>
			</div>

			<div class="clearfix"></div>

		</div>

		<div class="new-edit-footer">
			<a class="new-edit-cancel new-edit-btn" onclick="newTypeCancel()">Cancel</a>
			<a class="new-edit-continue new-edit-btn only-continue-btn" onclick="newTypeContinue()">Continue</a>
		</div>

	</div>

	<div class="yp-exit-confirm-bg" onclick="actionBtn('cancel', this);"></div>
	<div class="yp-exit-confirm-box">
		<h3>Do you want to save the current changes?</h3>
		<a class="action-btn" onclick="actionBtn('cancel', this);">Cancel</a><a class="action-btn" onclick="actionBtn('nosave', this);">Don't Save</a><a class="action-btn primary-btn" onclick="actionBtn('save', this);">Save</a>
	</div>

	</body>
</html>