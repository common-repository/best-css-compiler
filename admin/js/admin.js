function liftCompilerkeyDown(e) {
	var deny = "!@#$%^&*()+=[]\\\';,./{}|\":<>?`~";
	var e = window.event || e;
	var key = e.keyCode;
	if (key == 32) {
		e.preventDefault();
	}
	if (e.key.match(/[A-Z]/)) {
		e.preventDefault();
	};
	for (var i = 0; i < deny.length; i++) {
		if (deny.charAt(i) === e.key) {
			e.preventDefault();
		}
	}
}

jQuery(document).ready(function () {
	var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
	editorSettings.codemirror = _.extend(
		{},
		editorSettings.codemirror,
		{
			lineNumbers: true,
			styleActiveLine: true,
			mode: "text/x-scss",
			theme: "monokai",
			indentUnit: 4,
			indentWithTabs: true,
			lineWrapping: true,
		}
	);
	wp.codeEditor.initialize(jQuery('#code_editor_page_head'), editorSettings);
})

