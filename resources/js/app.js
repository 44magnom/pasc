import './bootstrap';
import tinymce from 'tinymce/tinymce';

// Thème
import 'tinymce/themes/silver';

// Icônes
import 'tinymce/icons/default';

// Skin
import 'tinymce/skins/ui/oxide/skin.css';

// Plugins
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/table';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/autolink';

// Le rendre disponible partout
window.tinymce = tinymce;