<?php

return [
    'intro' => 'Control your SEO general settings here. Make sure to read the instructions on each input. This settings can be overidden on specific entries/pages.',
    'meta_section' => 'Meta',
    'title' => 'Title',
    'title_instructions' => 'While the title tag doesn’t start with "meta," it is in the header and contains information that\'s very important to SEO. You should always have a unique title tag on every page that describes the page.',

    'title_prepend' => 'Prepend on Title',
    'title_prepend_instructions' => 'This will be PREPENDED to all of the titles.',

    'title_append' => 'Append on Title',
    'title_append_instructions' => 'This will be APPENDED to all of the titles.',

    'description_section' => 'Description',
    'description_section_instructions' => 'It is used for one major purpose: to describe the page to searchers as they read through the SERPs. This tag doesn\'t influence ranking, but it\'s very important regardless. It\'s the ad copy that will determine if users click on your result. Keep it within 160 characters, and write it to catch the user\'s attention. Sell the page — get them to click on the result.',

    'meta_description' => 'Meta Description',
    'meta_description_instructions' => 'Can be overriden on pages, if left blank, search engines will generate their own content for this field.',

    'social_section' => 'Social',
    'social_image' => 'Social Image',
    'social_image_instructions' => 'Use your logo or any other branded image for the rest of your pages. Use images with a 1.91:1 ratio and minimum recommended dimensions of 1200x630 for optimal clarity across all devices.',
    'social_open_graph' => 'Output Open Graph tags',
    'social_open_graph_instructions' => 'Open Graph tags are used to control how URLs are displayed when shared on social media. If you don\'t have Open Graph tags on your site, Facebook will try to scrape the content of the page to determine what to display. This can lead to issues with how your content is displayed, and it can also lead to Facebook displaying the wrong content.',
    'social_site_name' => 'Site Name',
    'social_site_name_instructions' => 'The name of your site. This will be displayed in the social media preview.',
    'social_title' => 'Title',
    'social_title_instructions' => 'Keep it short to prevent overflow. 40 characters for mobile and 60 for desktop is roughly the sweet spot. Use the raw title. Don’t include branding (e.g., your site name).',
    'social_description' => 'Description',
    'social_description_instructions' => 'General Description, can be overridden on specific pages. Complement the title to make the snippet as appealing and click-worthy as possible. Copy your meta description here if it makes sense. Keep it short and sweet. Facebook recommends 2–4 sentences, but that often truncates.',

    'social_twitter' => 'Output Twitter tags',
    'social_twitter_instructions' => 'Twitter cards are a way to attach rich photos, videos and media experience to Tweets that drive traffic to your website. You can use Twitter cards to help you drive traffic to your website, increase the visibility of your content, and get more engagement with your Tweets.',

    'meta_field_title_title' => 'Title',
    'meta_field_title_instructions' => 'It can be used to determine the title used on search engine results pages. Defaults to `title` which sets the page title as the Entry title. For custom entries, select `Custom` and enter your own value.',
    'meta_field_prepend_label' => 'Prepend to the title the text set in General SEO settings',
    'meta_field_append_label' => 'Append to the title the text set in General SEO settings',
    'meta_field_description_title' => 'Description',
    'meta_field_description_instructions' => 'It can be used to determine the text used under the title on search engine results pages. If empty, search engines will automatically generate this text.',
    'meta_field_default_description' => 'This description will be prefilled by the search engine depending on your content. You can change it manually by selecting custom and typing in your own.',
    'meta_field_label_title' => 'Title',
    'meta_field_label_custom' => 'Custom',
    'meta_field_label_empty' => 'Empty',
    'meta_field_preview_title' => 'Search preview',

    'social_field_title_title' => 'Social title',
    'social_field_title_instructions' => 'Social (Open Graph, Twitter, …) is used to display information while sharing the website link. OpenGraph is the most common sharing protocol (used by Facebook, Slack…). The title should be between 50 and 60 characters and not have any branding.',
    'social_field_description_title' => 'Social description',
    'social_field_description_instructions' => 'Shown below the title. It is used to describe the content of the page. If Meta description is not empty, it can be reused here. Usually the Meta description is longer.',
    'social_field_label_title' => 'Title',
    'social_field_label_general' => 'General',
    'social_field_label_custom' => 'Custom',
    'social_field_label_meta' => 'Meta',
    'social_field_preview_title' => 'Social preview',

    'canonical_display' => 'Canonical URL',
    'canonical_instructions' => 'Canonical URLs are used to tell search engines which URL is the original source of a page. This is useful when you have multiple URLs that point to the same page. For example, if you have a page that can be accessed with or without a trailing slash, you can use a canonical URL to tell search engines which one is the original. This helps search engines avoid duplicate content issues.',

    'social_image_display' => 'Social Image',
    'social_image_instructions' => 'This image will be used when sharing the page on social networks. If left empty, the default image will be used. It\'s best to use an image with a 1.91:1 aspect ratio that is at least 1200px wide for universal support.',
];
