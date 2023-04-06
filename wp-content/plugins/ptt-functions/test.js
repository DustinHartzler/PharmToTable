wp.blocks.registerBlockType("ptt/show-notes", {
	title: "Show Notes",
	icon: "smiley",
	category: "common",
	edit: function () {
		return wp.element.createElement("h2", {style: {fontFamily: 'silver_south_scriptregular', color:'#71CABC'}}, "Show Notes")
	},
	save: function() {
		return wp.element.createElement("h1", {style: {fontFamily: 'silver_south_scriptregular', color:'#71CABC'}}, "Show Notes")
	}
})
