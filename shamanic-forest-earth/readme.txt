=== Shamanic Forest & Earth ===
Contributors: smartsite
Requires at least: 6.0
Requires PHP: 7.4
Stable tag: 3.4.8
License: GPLv2 or later

Forest & Earth homepage and visual design for the NativeChurch theme.

== 3.4.8 ==
* Event pages: "Book your place" box above and below the description, with date and time, Book a place and Call buttons. After the event it links to the upcoming events instead.
* "Book a place" (event pages and events list) opens the booking form with "Event or Workshop" chosen and the message filled in with the event name and date. The booking form updates itself once, on the next visit to the WordPress dashboard.
* Switch in settings: "Event pages: show the Book your place box".

== 3.4.7 ==
* The Events page shows the Forest & Earth events list automatically: no shortcode or template change needed. The theme's old calendar template is swapped for the full-width layout. Switch in settings: "Events page: show the Forest & Earth events list automatically".

== 3.4.6 ==
* Events page: [sfe_events] – upcoming events as cards grouped by month (date tile, image, time, place, excerpt, Details + Book buttons); past events in a collapsible list.

== 3.4.5 ==
* Phones/tablets: tapping a menu item with a submenu (Sessions, About Me, Sound Healing) opens and closes it; clear gold chevron; theme's tiny toggles hidden. Desktop hover menu unchanged.

== 3.4.4 ==
* Phones: redesigned session pages stack cleanly (columns, price list, quote panels).

== 3.4.3 ==
* Built-in newsletter signup (name, email, consent, spam honeypot) that adds subscribers straight to a MailPoet list through the MailPoet API, with MailPoet's confirmation email. No MailPoet form needed. Used on the Newsletter page and in the homepage newsletter panel.
* Settings: optional MailPoet form ID (to use a MailPoet form instead) and MailPoet list ID (default: first standard list).

== 3.4.2 ==
* Newsletter page: [sfe_newsletter_form] shows the first active MailPoet form (or the one set in settings) in a Forest & Earth card with artwork.

== 3.4.1 ==
* Event pages: Forest & Earth styling for the "Event details" panel (green header, cream rows, gold icons), attendee/staff counters with gold badges, uniform share icons; Google+, VK, Tumblr and Reddit share buttons hidden.
* Old maroon headings typed into page/event content now use the forest green.

== 3.4.0 ==
* Speed: the theme's old Google Fonts (Roboto, Roboto Condensed, Volkhov, Anaheim) are no longer loaded; only Cormorant Garamond + Lato. Switch: "Speed: do not load the theme's old Google Fonts".
* Speed: early connection (preconnect) to the Google Fonts servers.
* Includes everything from 3.3.x (booking form, footer, consistent pages, Like buttons hidden).

== 3.3.7 ==
* Hides the Jetpack "Like this" buttons on every page and post (setting: "Hide the Jetpack Like buttons").

== 3.3.6 ==
* Booking form uses the full width: two fields per row, wide message box (CF7 auto-paragraphs switched off for this form only).
* Fixed: client review in the Contact sidebar no longer squeezed one word per line.

== 3.3.5 ==
* Booking form: "In person" instead of the town name; steps say "Meet in person or online". A form created by 3.3.4 is updated automatically once.
* Front-end guard for the NativeChurch "Custom JS" option: when it contains CSS (causing a JavaScript error on every page) it is not printed. The saved theme setting is unchanged.

== 3.3.4 ==
* Booking form: [sfe_booking_form] shortcode. Creates a Contact Form 7 form "Forest & Earth – Booking Request" automatically (name, email, phone, session type, format, preferred times, message, consent). Notification to the site email with Reply-To set to the client, plus an automatic confirmation to the client. Edit it under Contact > Contact Forms.
* Fixed: reviews in the Contact page sidebar were squeezed into one word per line.

== 3.3.3 ==
* Fixed: breadcrumb row under the menu was cut off (theme spacing restored).
* Fixed: large empty gap between the page-title band and the content.
* Homepage events: when no events are entered in settings, only future Event posts are shown (with their date); past events never appear.
* Dropdown menus have a solid background.

== 3.3.2 ==
* Fixed: homepage hero title was dark green on the dark photo (unreadable).
* Fixed: icons flashed very large while the page was loading (SVG sizes + early critical CSS).
* New Forest & Earth footer on every page: pine treeline, brand column, Healing Sessions and Explore links, contact details, booking button, Privacy Policy and Back to top.
* The theme's Footer Sidebar widgets (video, GoGetSSL seal, amung.us visitor counter) are no longer rendered, so their external scripts stop loading. They stay saved in Appearance > Widgets; untick "Use the Forest & Earth footer" to bring them back.

== 3.3.1 ==
* One consistent look on every page: forest-green menu everywhere, green page-title band with artwork, Cormorant Garamond + Lato fonts site-wide.
* Styled content: lists, quotes, images, tables, buttons, Contact Form 7, reviews, sidebar widgets, blog meta, pagination, footer.
* "Book a Healing Session" banner at the end of pages and posts (can be switched off in settings; skipped on Contact, Newsletter, policy pages).
* Shamanic artwork from the Media Library (IDs 20986-20990) used for the hero, About section, event cards and page headers. Replace any of them in settings.
* Removed the conflicting cream menu rules from 3.2.x.

== 3.3.0 ==
* Top contact bar on every page: phone, email, social icons, "Book a Session" button (Settings > Forest & Earth).
* Four-service strip under the hero with line icons.
* Upcoming events with date badges, entered in settings (YYYY-MM-DD | Title | Location | Link | Text). Past dates hide automatically.
* Client review slider reading real reviews from WP Customer Reviews (4-5 stars).
* Latest blog posts with a newsletter panel; uses the first active MailPoet form automatically.
* Fixed: Shamanic Journey link (/shamanic-journey/), Counselling card links to /shamanic-counselling-coaching/.
* Cormorant Garamond + Lato fonts. Hero and About images can be replaced in settings.

== Installation ==
1. Deactivate the previous Shamanic Forest & Earth version (do not delete it, it is the rollback).
2. Upload and activate 3.4.6. Existing 3.2 switches are kept.
3. Open Settings > Forest & Earth, check details, add events, save.
4. Clear cache (Perfmatters / browser).

== Rollback ==
Deactivate 3.4.6 and reactivate the previous version. No pages, menus or theme files are edited.
