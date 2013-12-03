<?

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\HTML;
use Orchestra\Support\Facades\Asset; ?>

<meta charset="utf-8">
{{ HTML::title() }}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Orchestra Platform">
<meta name="author" content="Orchestra Platform">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300,500" rel="stylesheet" type="text/css">

<?

$asset = Asset::container('orchestra/foundation::header');

$asset->style('select2', 'packages/orchestra/foundation/vendor/select2/select2.css');
$asset->style('jquery-ui', 'packages/orchestra/foundation/vendor/delta/theme/jquery-ui.css');
$asset->style('bootstrap', 'packages/orchestra/foundation/vendor/bootstrap/css/bootstrap.min.css');
$asset->style('orchestra', 'packages/orchestra/foundation/css/style.css', array('bootstrap', 'select2'));
$asset->script('underscore', 'packages/orchestra/foundation/vendor/underscore.min.js');
$asset->script('jquery', 'packages/orchestra/foundation/vendor/jquery/jquery.min.js');
$asset->script('javie', 'packages/orchestra/foundation/vendor/javie/javie.min.js', array('jquery', 'underscore')); ?>

{{ $asset->styles() }}
{{ $asset->scripts() }}

<script>
Javie.ENV = "{{ App::environment() }}";
</script>

@placeholder("orchestra.layout: header")
