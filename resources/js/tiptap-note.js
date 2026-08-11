import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

let editorRecto = null;
let editorVerso = null;

document.addEventListener('DOMContentLoaded', function () {

    const rectoElement =
        document.getElementById('recto');

    const versoElement =
        document.getElementById('verso');


    if (!rectoElement || !versoElement) {

        console.error(
            '❌ Éléments Tiptap introuvables'
        );

        return;
    }


    editorRecto = new Editor({

        element: rectoElement,

        extensions: [
            StarterKit
        ],

        content: '',

    });


    editorVerso = new Editor({

        element: versoElement,

        extensions: [
            StarterKit
        ],

        content: '',

    });


    window.editorRecto = editorRecto;
    window.editorVerso = editorVerso;


    console.log(
        '✅ Tiptap Recto initialisé'
    );

    console.log(
        '✅ Tiptap Verso initialisé'
    );

});