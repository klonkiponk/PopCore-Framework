// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// Html tags
// http://en.wikipedia.org/wiki/html
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings = {
	nameSpace:'html',
	onShiftEnter:	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
	onTab:			{keepDefault:false, openWith:'	 '},
	markupSet: [
		{name:'Heading 4', key:'4', openWith:'<span class="h4">', closeWith:'</span>', placeHolder:'Your title here...' },
		{name:'Heading 5', key:'5', openWith:'<span class="h5">', closeWith:'</span>', placeHolder:'Your title here...' },
		{name:'Heading 6', key:'6', openWith:'<span class="h6">', closeWith:'</span>', placeHolder:'Your title here...' },
		{name:'Paragraph', openWith:'<p(!( class="[![Class]!]")!)>', closeWith:'</p>' },
		{separator:'---------------' },
		{name:'Bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
		{name:'Italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)' },
		{name:'Stroke through', openWith:'<del>', closeWith:'</del>' },
		{separator:'---------------' },
		{name:'Ul', openWith:'<ul>\n', closeWith:'</ul>\n' },
		{name:'Ol', openWith:'<ol>\n', closeWith:'</ol>\n' },
		{name:'Li', openWith:'<li>', closeWith:'</li>\n' },
		{separator:'---------------' },
		{name:'Picture', key:'P', replaceWith:'<a href="[![ImageZoom URL:!:http://]!]" class="imageZoom"><img src="[![ImageUrl:!:http://]!]" alt="" class="[![class:!:small shadow floatright]!]"/></a>' },
		{name:'Link', key:'L', openWith:'<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
		{separator:'---------------' },
		{name:'Homework', openWith:'<div class="homework">', closeWith:'</div>\n'  },
		{name:'Information', openWith:'<div class="information">', closeWith:'</div>\n'  },
		{name:'Emphasize', openWith:'<div class="emphasize">', closeWith:'</div>\n'  },
		{separator:'---------------' },
		{name:'ABBR', openWith:'<abbr title="">', closeWith:'</abbr>'  },		
		{name:'TAG', openWith:'<span class="tag">', closeWith:'</span>'  },
		{name:'Attribute', openWith:'<span class="attribute">', closeWith:'</span>'  }
		
	]
}