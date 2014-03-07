

{#<div class="sidebar">

	{% if session.get('identity') %}
		{{ link_to('post/discussion', '글쓰기 시작하기', 'class': 'btn btn-large btn-info', 'rel': 'nofollow') }}
	{% else %}
		{{ link_to('login/oauth/authorize', 'Github로 로그인하기', 'class': 'btn btn-large btn-info', 'rel': 'nofollow') }}
	{% endif %}

	{% cache "sidebar" %}
	<ul class="nav nav-tabs nav-stacked">
	{% for category in categories %}
		<li>
			{{ link_to('category/' ~ category.id ~ '/' ~ category.slug,
				category.name ~ '<span class="number-posts label">' ~ category.number_posts ~ '</span>')
			}}
		</li>
	{% endfor %}
	</ul>
	{% endcache %}

</div>#}
