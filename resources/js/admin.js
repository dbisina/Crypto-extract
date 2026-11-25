import 'trix';
import 'trix/dist/trix.css';

// Disable Trix file attachments' default upload (we use the media library instead).
document.addEventListener('trix-file-accept', (e) => e.preventDefault());
