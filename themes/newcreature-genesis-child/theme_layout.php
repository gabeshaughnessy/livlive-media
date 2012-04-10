
<?php 
//	This is the home page template, kinda...
// This is going to be a couple genesis functions to remove some content and add some new content
// using the get_template_part function and the built in Genesis content loops and grids.
//But for now its just a rough outline while I build all that stuff
?><html>
<head>
</head>

<body>

<div id="wrap">

<page>

<header>
<title></title>
<logo></logo>
</header>

<menu><!-- WordPress menu you can control in the back-end. You can re-arrange the menu items and change their names, just don't delete them or add any new ones, and they will link to the different sections of the home page properly -->
<menuitem></menuitem><!-- links to #ID of a Div to trigger smooth scroll -->
</menu>

<!-- Need to add the slider in after the menu, before the content -->
<?php // use get_template_part('slider'); ?>
<slider>
<?php // Get Featured Post Loop ?>
<slides><!-- smoothscroll link to the Div on the same page and load the ajax content -->
<slidetitle></slidetitle>
<slidesubtitle></slidesubtitle>
<sliderbackground></sliderbackground>
</slides>
</slider>

<slidepager>
<pageritem></pageritem><!-- controls slideshow playback -->
</slidepager>

<?php //use get_template_part('content_block', 'page'); ?>
<content_block><!-- content block for pages like 'About' or "Contact" -->
<page_1_content></page_1_content><!-- Displays the Title and Content of a Wordpress Page
</content_block>

<?php //use genesis post feed ?>
<content_block><!-- Content Block for Post Feeds, showing a set of posts -->
<news-feed></news-feed>
</content_block>

<content_block><!-- Content Block for Post Grids, also showing a set of posts but formatted differntly then the feeds -->
<grid><?php //use genesis post grid; ?>
<posttitle></posttitle>
<postdescription></postdescription>
<postthumbnail></postthumbnail>
<!-- Ajax loading of the the_content(); -->
</grid>
</content_block>

</page>

<footer>
<!-- edit css to have a footer image that you can upload from the options page-->
</footer>

</div>

</body>
</html>