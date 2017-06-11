function update_css(values) {
  for (var index in values) {
	switch (index) {
	  case "text_color":
		document.querySelector('.glossary-tooltip-text').style.color = values[index];
		break;
	  case "text_background":
		document.querySelector('.glossary-tooltip-text').style.background = values[index];
		document.querySelector('.glossary-tooltip-content').style.background = values[index];
		break;
	  case "text_size":
		document.querySelector('.glossary-tooltip-text').style.fontSize = values[index];
		break;
	  case "lemma_color":
		document.querySelector('.glossary-tooltip-item a').style.color = values[index];
		break;
	  case "lemma_background":
		document.querySelector('.glossary-tooltip-item a').style.background = values[index];
		break;
	  case "lemma_size":
		document.querySelector('.glossary-tooltip-item a').style.fontSize = values[index];
		break;
	  case "link_lemma_color":
		document.querySelector('.glossary-tooltip-text a').style.color = values[index];
		break;
	}
  };
}