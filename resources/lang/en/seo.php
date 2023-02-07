<?php

return [
    /**
     * Single Entry SEO
     */
    'tab_title' => 'SEO',

    'section_meta_title' => 'Meta',
    'section_meta_instructions' => 'Meta tags are essentially little content descriptors that help tell search engines what a web page is about.',

    'meta_title' => 'SEOtamic Meta',

    'meta_title_title' => 'Title',
    'meta_title_instructions' => 'Focus on accuracy, value, and clickability. Keep it short to prevent overflow. There’s no official guidance on this, but 40 characters for mobile and 60 for desktop is roughly the sweet spot. Use the raw title. Don’t include branding (e.g., your site name).',
    'meta_prepend_label' => 'Prepend to the title the text set in the SEOtamic settings',
    'meta_append_label' => 'Append to the title the text set in the SEOtamic settings',

    'meta_description_title' => 'Description',
    'meta_description_instructions' => 'It can be used to determine the text used under the title on search engine results pages. If empty, search engines will generate this text automatically. It is best to leave this empty and let the search engine generate it automatically.',
    'meta_default_description' => 'This description will be prefilled by the search engine depending on your content. You can change it manually by selecting custom and typing in your own.',

    'meta_label_title' => ' Entry Title',
    'meta_label_auto' => 'Auto',
    'meta_label_custom' => 'Custom',
    'meta_label_empty' => 'Empty',
    'meta_preview_title' => 'Search preview',

    'canonical_title' => 'Canonical URL',
    'canonical_instructions' => 'Canonical URLs are used to tell search engines which URL is the original source of a page. This is useful when you have multiple URLs that point to the same page. For example, if you have a page that can be accessed with or without a trailing slash, you can use a canonical URL to tell search engines which one is the original. This helps search engines avoid duplicate content issues.',

    'robots_title' => 'Disable page indexing (noindex, nofollow)',
    'robots_instructions' => 'This will prevent this page from being indexed by search engines. This is useful for pages that are not ready for public viewing, or for pages that you don\'t want to be indexed by search engines.',
];
