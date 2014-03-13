<header>
	<nav class="navbar navbar-reverse" role="navigation">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="http://phalconphp.kr">팔콘코리아</a>
		  <a class="navbar-brand" href="http://forum.phalconphp.kr">포럼</a>
		</div>

		<div class="collapse navbar-collapse">
		  <ul class="nav navbar-nav navbar-right">
			{%- if session.get('identity') -%}
				<li>{{ link_to('post/discussion', '글쓰기', 'class': 'btn btn-default btn-info', 'rel': 'nofollow') }}</li>
			{%- else -%}
				<li>{{ link_to('login/oauth/authorize', 'Github로 로그인하기', 'class': 'btn btn-default btn-info', 'rel': 'nofollow') }}</li>
			{%- endif -%}
			<li>{{ link_to('', '<span class="glyphicon glyphicon-comment"></span>', 'title': '게시판') }}</li>
			<li class="dropdown">
          		<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Search">
          			<span class="glyphicon glyphicon-search"></span> <b class="caret"></b>
          		</a>
          		<ul class="dropdown-menu">
					<li>
						<div style="width:300px">
							<gcse:searchbox-only></gcse:searchbox-only>
						</div>
					</li>
				</ul>
          	</li>
			<li>{{ link_to('activity', '<span class="glyphicon glyphicon-eye-open"></span>', 'title': '활동') }}</li>

			<li class="dropdown">

				<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Categories">
					<span class="glyphicon glyphicon-th-list"></span> <b class="caret"></b>
				</a>

				{% cache "sidebar" %}
					<ul class="dropdown-menu">
						{% if categories is defined %}
							{% for category in categories %}
								<li>
									{{ link_to('category/' ~ category.id ~ '/' ~ category.slug,
										category.name ~ '<span class="label label-default" style="float: right">' ~ category.number_posts ~ '</span>')
									}}
								</li>
							{% endfor %}
						{% endif %}
					</ul>
				{% endcache %}
			</li>

			<li>{{ link_to('help', '<span class="glyphicon glyphicon-question-sign"></span>', 'title': '도움말') }}</li>

			{% if session.get('identity') %}
			<li>{{ link_to('settings', '<span class="glyphicon glyphicon-cog"></span>', 'title': '설정') }}</li>
			<li>{{ link_to('logout', '<span class="glyphicon glyphicon-off"></span>', 'title': '로그아웃') }}</li>
			{% endif %}
		  </ul>
		</div>
	  </div>
	</nav>
</header>
