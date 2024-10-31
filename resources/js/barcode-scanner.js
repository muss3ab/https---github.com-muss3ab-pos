class BarcodeScanner {
    constructor(callback) {
        this.callback = callback;
        this.barcode = '';
        this.listening = false;

        document.addEventListener('keydown', this.handleKeydown.bind(this));
    }

    handleKeydown(event) {
        // Check if input is focused
        if (document.activeElement.tagName === 'INPUT') return;

        // Start listening on first numeric input
        if (!this.listening && event.key >= '0' && event.key <= '9') {
            this.listening = true;
            this.barcode = '';
            this.lastKeyTime = Date.now();
        }

        if (this.listening) {
            // Add character to barcode
            this.barcode += event.key;

            // Check if it's been too long since last key (regular typing vs scanner)
            const currentTime = Date.now();
            if (currentTime - this.lastKeyTime > 100) {
                this.reset();
                return;
            }
            this.lastKeyTime = currentTime;

            // Check if barcode is complete (usually ends with Enter)
            if (event.key === 'Enter') {
                this.callback(this.barcode);
                this.reset();
            }
        }
    }

    reset() {
        this.listening = false;
        this.barcode = '';
    }
} 
