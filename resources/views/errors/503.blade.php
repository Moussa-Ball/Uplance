
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Uplance') }}</title>
    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="/internet.png">
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="wrapper">
	<header id="header-container" class="fullwidth">
		<!-- Header -->
		<div id="header">
			<div class="container">
				<div class="left-side" style="text-align: center; position: relative;top: 20px;">
					<div>
						<a href="/"><img src="data:image/png;base64,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" alt=""></a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<div class="clearfix"></div>
	<div class="margin-top-70"></div>
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<section id="not-found" class="center margin-top-50 margin-bottom-25">
					<h2>503 <i class="icon-line-awesome-question-circle"></i></h2>
					<p>Sorry, we are under maintenance. Please wait, we will be back soon.</p>
				</section>
			</div>
		</div>
	</div>
	<div class="margin-top-70"></div>
</body>
</html>