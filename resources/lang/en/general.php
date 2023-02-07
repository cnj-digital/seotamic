<?php

return [
    /**
     * Global configuration translation
     */
    'seotamic' => 'SEOtamic',
    'intro' => 'Control your SEO general settings here. Make sure to read the instructions on each input. These settings can be overidden on specific entries/pages.',

    'title' => 'Settings',

    /**
     * Title Tab
     */
    'title_title' => 'Title',

    'title_section_title' => 'Title',
    'title_section_instructions' => 'You should always have a unique title tag on every page that describes the page. By default SEOtamic uses the Entry title as the title, which you can edit in the SEO settings for each page.',

    'title_prepend_title' => 'Prepend on Title',
    'title_prepend_instructions' => 'This will be PREPENDED to all titles throughout the page. Not commonly used.',

    'title_append_title' => 'Append on Title',
    'title_append_instructions' => 'This will be APPENDED to all titles throughout the page. Commonly used for your brand/product name.',

    'title_preview_title' => 'Search preview (requires reload on save)',
    'title_preview_placeholder_title' => 'Demo Page Title',
    'title_preview_placeholder_description' => 'This description will be prefilled by the search engine depending on your content. You can change it manually by selecting custom and typing in your own.',

    /**
     * Social Tab
     */
    'social_title' => 'Social',

    'social_site_name_title' => 'Site Name',
    'social_site_name_instructions' => 'The name of your site. This might be displayed while sharing on socials. Usually this is the name of your brand/product. This is different than the website title, which is meant for describing the content of a specififc page. Example: Site name: *Apple* Page title: *iPhone 14 Pro specifications*.',

    'social_info_title' => 'General Social Settings',
    'social_info_instructions' => 'The best practice for social shares is to create unique content for each page. Since this not always an option, it is advised to set this for the most shared landing pages. The information below will be used as a fallback for all other pages.',

    'social_title_title' => 'Title',
    'social_title_instructions' => 'Focus on accuracy, value, and clickability. Keep it short to prevent overflow. 40 characters for mobile and 60 for desktop is roughly the sweet spot. Use the raw title. Don’t include branding (e.g. your site name).',

    'social_description_title' => 'Description',
    'social_description_instructions' => 'General Description, can be overridden on specific pages. Complement the title to make the snippet as appealing and click-worthy as possible. Copy your meta description here if it makes sense. Keep it short and sweet. Facebook recommends 2–4 sentences, but that often truncates.',

    'social_image_title' => 'Share Image',
    'social_image_instructions' => 'Use your logo or any other branded image for the default image. Use images with a 1.91:1 ratio and minimum recommended dimensions of 1200x630 for optimal clarity across all devices.',

    'social_image_compress_title' => 'Compress & Resize Share Image',
    'social_image_compress_instructions' => 'This will compress and resize the image to 1200x630. This will compress all social share images. Do not use this if you are using a custom image resolution.',

    'social_preview_title' => 'Social preview (requires reload on save)',

    'social_open_graph_title' => 'Output Open Graph tags',
    'social_open_graph_instructions' => 'Open Graph tags are used to control how URLs are displayed when shared on social media. If you don\'t have Open Graph tags on your site, Facebook will try to scrape the content of the page to determine what to display. This can lead to issues with how your content is displayed, and it can also lead to Facebook displaying the wrong content.',

    'social_twitter_title' => 'Output Twitter tags',
    'social_twitter_instructions' => 'Twitter cards are a way to attach rich photos, videos and media experience to Tweets that drive traffic to your website. You can use Twitter cards to help you drive traffic to your website, increase the visibility of your content, and get more engagement with your Tweets.',

    /**
     * Settings Tab
     */
    'settings_title' => 'Settings',

    'settings_preview_domain_title' => 'Preview Domain/URL',
    'settings_preview_domain_instructions' => 'This is the domain that will be used to generate the social and SEO previews.',

    'settings_robots_title' => 'Disable page indexing (noindex, nofollow)',
    'settings_robots_instructions' => 'This will prevent the page from being indexed by search engines. This is useful for pages that are not ready for public viewing, or for pages that you don\'t want to be indexed by search engines This will be set for all pages.',
];
