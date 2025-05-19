<template>
    <!-- <pre v-if="content"
            class="w-[1200px] h-[90%] flex bg-black border border-gray-500"
            name=""
            id="testlg"
            >

            <code class="language-java">
                {{ content }}
            </code>

        </pre> -->
    <div class="w-full">
        <prism :content=content :language="language" />
    </div>
</template>

<script setup>

import { defineProps } from 'vue';
import Prism from '@/Components/Prism.vue';

const props = defineProps({
    content: Object,
    filename: String
})
var EXTENSIONS = {
    'js': 'javascript',
    'mjs': 'javascript',
    'cjs': 'javascript',
    'ts': 'typescript',
    'tsx': 'tsx',
    'jsx': 'jsx',
    'py': 'python',
    'rb': 'ruby',
    'php': 'php',
    'java': 'java',
    'cs': 'csharp',
    'ps1': 'powershell',
    'psm1': 'powershell',
    'sh': 'bash',
    'bash': 'bash',
    'zsh': 'bash',
    'bat': 'batch',
    'cmd': 'batch',
    'c': 'c',
    'h': 'c',
    'cpp': 'cpp',
    'cxx': 'cpp',
    'cc': 'cpp',
    'hpp': 'cpp',
    'go': 'go',
    'rs': 'rust',
    'swift': 'swift',
    'kt': 'kotlin',
    'kts': 'kotlin',
    'scala': 'scala',
    'lua': 'lua',
    'r': 'r',
    'sql': 'sql',
    'html': 'markup',
    'htm': 'markup',
    'xml': 'markup',
    'xhtml': 'markup',
    'json': 'json',
    'yml': 'yaml',
    'yaml': 'yaml',
    'ini': 'ini',
    'toml': 'toml',
    'md': 'markdown',
    'markdown': 'markdown',
    'tex': 'latex',
    'scss': 'scss',
    'sass': 'sass',
    'less': 'less',
    'css': 'css',
    'dockerfile': 'docker',
    'docker': 'docker',
    'makefile': 'makefile',
    'gradle': 'gradle',
    'groovy': 'groovy',
    'asm': 'nasm'
};

var extension = (/\.(\w+)$/.exec(props.filename) || [, 'none'])[1];
var language = "language-" +( EXTENSIONS[extension] || extension);
console.log(language)
</script>
