{{ content() }}

<div class="view-discussion container">

	<p>
		<h1>최근 활동</h1>
	</p>

	<ul class="nav nav-tabs">
		{%- set orders = ['': '포럼', '/irc': 'IRC'] %}
		{%- for order, label in orders -%}
			{%- if order == '' -%}
			<li class="active">
			{%- else -%}
			<li>
			{%- endif -%}
			{{ link_to('activity' ~ order, label) }}
			</li>
		{%- endfor -%}
	</ul>

	<table width="90%" align="center" class="table table-striped">
		{%- for activity in activities -%}
		<tr>
			<td class="small hidden-xs" valign="top">
				<img src="https://secure.gravatar.com/avatar/{{ activity.user.gravatar_id }}?s=24&amp;r=pg&amp;d=identicon" class="img-rounded">
			</td>
			<td>
				<div class="activity">
					<span>{{ link_to('user/' ~ activity.user.id ~ '/' ~ activity.user.login, activity.user.name|e) }} </span> 님이
					<span class="date"> {{ activity.getHumanCreatedAt() }} </span> 
					{%- if activity.type == 'U' -%}
					포럼에 가입하셨습니다.
					{%- elseif activity.type == 'P' -%}
					{{ link_to('discussion/' ~ activity.post.id ~ '/' ~ activity.post.slug, activity.post.title|e) }} 글을 남기셨습니다.
					{%- elseif activity.type == 'C' -%}
					{{ link_to('discussion/' ~ activity.post.id ~ '/' ~ activity.post.slug, activity.post.title|e) }} 댓글을 남기셨습니다.
					{%- endif -%}
				</div>
			</td>
		</tr>
		{%- endfor -%}
	</table>

</div>
