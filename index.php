<?php

require __DIR__ . '/vendor/autoload.php';


header('X-Frame-Options: ALLOW-FROM https://app.storyblok.com');
header('Cache-Control: no-cache, no-store, private, must-revalidate, max-age=0, max-stale=0, post-check=0, pre-check=0');
header('Pragma: no-cache');
header('Expires: 0');

# GLOBAL VARS
$token = getenv('STORYBLOK_API_KEY');
$token = "Op15qvjU7G5JtRZMz88glQtt";
$slug = (empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] ==  '/') ? false : $_SERVER['REQUEST_URI'];
$slug = str_replace('/storyblok/','/',$slug);

$twig_root_template = ($slug) ? '_root.html' : '_components.html';

if(isset($_GET['layout'])){
    $twig_root_template = '_'.htmlspecialchars($_GET['layout']).'.html';
}

# STORYBLOK
$client = new \Storyblok\Client($token);
// Optionally set a cache
$client->setCache('filesytem', array('path' => 'var/cache/storyblok'));
$client->resolveLinks('story');
$client->resolveRelations('faq.items'); // declare relationship
if($slug){
    $client->getStoryBySlug($slug); // Get the story searching for the slug
}

# FLUSH STORYBLOK CACHE IF story is 60s old
if(time()-$client->getCacheVersion()>60){
    $client->deleteCacheBySlug($slug);
    $client->getStoryBySlug($slug);
}

# PREPARE DATA FOR TWIG
$data = $client->getBody();
$data['public_token'] = $token; // add token to twig for js bridge

# TWIG
$loader = new \Twig\Loader\FilesystemLoader([__DIR__ . '/components',__DIR__ . '/components/layouts']);
$twig = new \Twig\Environment($loader, [
    'debug' => false,
    // 'cache' => __DIR__.'var/cache/twig/',
]);

# TWIG FILTERS
$filters = [];

// to debug twig variable use : {{ variable | debug }}
$filters[] = new \Twig\TwigFilter('debug', function ($var){ echo '<pre>'.print_r($var,true).'</pre>';} );

$filters[] = new \Twig\TwigFilter('resize', function ($asset, array $options = []) {

    $size = $options[0];

    if (!$asset['filename']) {
        $_size = explode('x', $size);
        if ($_size[0] == "0") {
            $size = $_size[1];
        }

        if ($_size[1] == "0") {
            $size = $_size[0];
        }
        return 'https://via.placeholder.com/' . $size;
    }

    // add focus feature
    $filter_focal = ($asset['focus']) ? 'filters:focal('.$asset['focus'].')/' : '';

    $image = parse_url($asset['filename']);
    return $image['scheme'] . '://img2.storyblok.com/' . $size . '/' .$filter_focal . $image['path'];
}, ['is_variadic' => true]);

$filters[] = new \Twig\TwigFilter('richtext', function ($richtext) {
    if(!is_array($richtext)){
        return $richtext;
    }
    $resolver = new Storyblok\RichtextRender\Resolver();
    return (string) $resolver->render($richtext);
},['pre_escape' => 'html', 'is_safe' => ['html']]);
# LOADING FILTERS
foreach($filters as $filter){
    $twig->addFilter($filter);
}

# RENDER
$template = $twig->load($twig_root_template);
echo $template->render($data);

# DEBUG
//echo'<pre>';
//print_r($data);
//echo'</pre>';
?>

<!-- ONLY FOR STORYBLOK PURPOSE -->
<script type="text/javascript">
    window.OluApp.init();
</script>