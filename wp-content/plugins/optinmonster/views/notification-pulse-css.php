<style type="text/css">
	.om-pulse{
		text-align: center;
		cursor: pointer;
		z-index: 99;
		left: 123px;
		top: 7px;
	}
	.om-pulse, .om-pulse:before {
		display: inline-block;
		width: 12px;
		height: 12px;
		border-radius: 100%;
		background-color: #74ba0d;
		position: absolute;
		top: 7px;
		right: 40px;
	}
	.om-pulse:before {
		content: '';
		border: 1px solid #74ba0d;
		left: -3px;
		top: -3px;
		width: 16px;
		height: 16px;
		background: transparent;
	}
	.om-pulse:after{
		content: '';
		position: absolute;
		top: -2px;
		left: -2px;
		width: 16px;
		height: 16px;
		background-color: #7dd004;
		border-radius: 100%;
		z-index: -1;
		animation: ompulse 2s infinite;
		will-change: transform;
	}

	.om-pulse:hover:after{
		animation: none;
	}

	@-webkit-keyframes ompulse{
		0%{transform: scale(1);opacity: 1;}
		100%{transform: scale(2);opacity: 0;}
	}
	@-moz-keyframes ompulse{
		0%{transform: scale(1);opacity: 1;}
		100%{transform: scale(2);opacity: 0;}
	}
	@keyframes ompulse{
		0%{transform: scale(1);opacity: 1;}
		100%{transform: scale(2);opacity: 0;}
	}
</style>
