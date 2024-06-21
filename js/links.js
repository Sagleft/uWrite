var Link = Quill.import('formats/link');

class CustomLink extends Link {
  static create(value) {
    let node = super.create(value);
    // Support for custom schemes
    if (value.startsWith('utopia://')) {
      node.setAttribute('href', value);
    } else {
      // If the scheme is not custom, use the standard behavior
      node.setAttribute('href', Link.sanitize(value));
    }
    return node;
  }

  static formats(domNode) {
    // Support for custom schemes
    if (domNode.getAttribute('href').startsWith('utopia://')) {
      return domNode.getAttribute('href');
    } else {
      return Link.formats(domNode);
    }
  }
}

// Replace the standard Link blot with the custom one
Quill.register(CustomLink);
