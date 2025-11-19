/**
 * Advanced Text Splitter Utility
 * FREE alternative to GSAP SplitText (which is a paid plugin)
 * Provides sophisticated text splitting with responsive handling
 */
(function() {
    'use strict';

    /**
     * EGW Text Splitter Class
     */
    class EGWTextSplitter {
        constructor(element, options = {}) {
            this.element = typeof element === 'string' ? document.querySelector(element) : element;

            if (!this.element) {
                console.error('EGWTextSplitter: Invalid element');
                return;
            }

            this.options = {
                type: options.type || 'lines,words,chars', // 'lines', 'words', 'chars', or combination
                linesClass: options.linesClass || 'egw-line',
                wordsClass: options.wordsClass || 'egw-word',
                charsClass: options.charsClass || 'egw-char',
                absolute: options.absolute || false,
                reduceWhiteSpace: options.reduceWhiteSpace !== false,
                preserveSpaces: options.preserveSpaces !== false,
                tag: options.tag || 'div'
            };

            this.originalHTML = this.element.innerHTML;
            this.originalText = this.element.textContent;
            this.lines = [];
            this.words = [];
            this.chars = [];

            this.split();
        }

        /**
         * Main split function
         */
        split() {
            const types = this.options.type.split(',').map(t => t.trim());

            if (types.includes('lines') || types.includes('words') || types.includes('chars')) {
                this.performSplit(types);
            }

            return this;
        }

        /**
         * Perform the split operation
         */
        performSplit(types) {
            // Store original styles
            const originalDisplay = this.element.style.display;

            let html = this.element.innerHTML;

            // Split into lines first if requested
            if (types.includes('lines')) {
                html = this.splitLines(html);
            }

            // Split into words if requested
            if (types.includes('words') || types.includes('chars')) {
                html = this.splitWords(html, types.includes('chars'));
            }

            // Update element
            this.element.innerHTML = html;

            // Store references
            this.lines = Array.from(this.element.querySelectorAll(`.${this.options.linesClass}`));
            this.words = Array.from(this.element.querySelectorAll(`.${this.options.wordsClass}`));
            this.chars = Array.from(this.element.querySelectorAll(`.${this.options.charsClass}`));

            // Add accessibility attributes
            this.element.setAttribute('aria-label', this.originalText);
            this.chars.forEach(char => char.setAttribute('aria-hidden', 'true'));
            this.words.forEach(word => word.setAttribute('aria-hidden', 'true'));
            this.lines.forEach(line => line.setAttribute('aria-hidden', 'true'));
        }

        /**
         * Split text into lines based on visual layout
         */
        splitLines(html) {
            // Create temporary element to measure line breaks
            const temp = document.createElement('div');
            temp.style.cssText = window.getComputedStyle(this.element).cssText;
            temp.style.position = 'absolute';
            temp.style.visibility = 'hidden';
            temp.style.width = this.element.offsetWidth + 'px';
            temp.innerHTML = html;
            document.body.appendChild(temp);

            const words = this.getTextNodes(temp);
            const lines = [];
            let currentLine = [];
            let lastTop = null;

            words.forEach((node, index) => {
                const range = document.createRange();
                range.selectNode(node);
                const rect = range.getBoundingClientRect();

                if (lastTop === null) {
                    lastTop = rect.top;
                }

                if (rect.top !== lastTop && currentLine.length > 0) {
                    lines.push(currentLine);
                    currentLine = [];
                    lastTop = rect.top;
                }

                currentLine.push(node.textContent);
            });

            if (currentLine.length > 0) {
                lines.push(currentLine);
            }

            document.body.removeChild(temp);

            // Wrap each line
            const wrappedLines = lines.map(line => {
                const lineText = line.join(' ');
                return `<${this.options.tag} class="${this.options.linesClass}" style="display: block; text-align: inherit; position: relative;">${lineText}</${this.options.tag}>`;
            }).join('');

            return wrappedLines;
        }

        /**
         * Split text into words
         */
        splitWords(html, splitChars = false) {
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html;

            const processNode = (node) => {
                if (node.nodeType === Node.TEXT_NODE) {
                    const text = node.textContent;
                    if (!text.trim()) return node;

                    // Split by whitespace but preserve spaces
                    const words = text.split(/(\s+)/);
                    const fragment = document.createDocumentFragment();

                    words.forEach(word => {
                        if (word.match(/\s+/) && this.options.preserveSpaces) {
                            // It's a space
                            fragment.appendChild(document.createTextNode(word));
                        } else if (word.trim()) {
                            // It's a word
                            const wordSpan = document.createElement(this.options.tag);
                            wordSpan.className = this.options.wordsClass;
                            wordSpan.style.cssText = 'display: inline-block; position: relative;';

                            if (splitChars) {
                                // Split word into characters
                                const chars = word.split('');
                                chars.forEach(char => {
                                    const charSpan = document.createElement('span');
                                    charSpan.className = this.options.charsClass;
                                    charSpan.style.cssText = 'display: inline-block; position: relative;';
                                    charSpan.textContent = char;
                                    wordSpan.appendChild(charSpan);
                                });
                            } else {
                                wordSpan.textContent = word;
                            }

                            fragment.appendChild(wordSpan);
                        }
                    });

                    return fragment;
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    // Process child nodes recursively
                    Array.from(node.childNodes).forEach(child => {
                        const processed = processNode(child);
                        if (processed !== child) {
                            node.replaceChild(processed, child);
                        }
                    });
                    return node;
                }
                return node;
            };

            Array.from(wrapper.childNodes).forEach(child => {
                processNode(child);
            });

            return wrapper.innerHTML;
        }

        /**
         * Get all text nodes
         */
        getTextNodes(element) {
            const textNodes = [];
            const walker = document.createTreeWalker(
                element,
                NodeFilter.SHOW_TEXT,
                null,
                false
            );

            let node;
            while (node = walker.nextNode()) {
                if (node.textContent.trim()) {
                    textNodes.push(node);
                }
            }

            return textNodes;
        }

        /**
         * Revert to original HTML
         */
        revert() {
            this.element.innerHTML = this.originalHTML;
            this.lines = [];
            this.words = [];
            this.chars = [];
            this.element.removeAttribute('aria-label');
            return this;
        }

        /**
         * Responsive reflow - revert and re-split
         */
        reflow() {
            const originalTypes = this.options.type;
            this.revert();
            this.split();
            return this;
        }
    }

    // Export to global namespace
    window.EGWTextSplitter = EGWTextSplitter;

    // Also export to EGW namespace if it exists
    if (window.EGW) {
        window.EGW.TextSplitter = EGWTextSplitter;
    }

})();
