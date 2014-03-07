
<div class="container">

	<ul class="nav nav-tabs">
		{%- set orders = [
			'new': '새로운 글',
			'hot': '활발한 글',
			'unanswered': '답변 없음',
			'my': '나의 글',
			'answers':'나의 답변'
		] -%}
		{%- for order, label in orders -%}
			{%- if (order == 'my' or order == 'answers') and !session.get('identity') -%}
				{%- continue -%}
			{% endif -%}
			{%- if order == currentOrder -%}
				<li class="active">
			{%- else -%}
				<li>
			{%- endif -%}
				{{ link_to('discussions/' ~ order, label) }}
			</li>
		{%- endfor -%}
	</ul>
</div>

{%- if posts|length -%}
<div class="container">
	<br/>
	<div align="center">
		<table class="table table-striped list-discussions" width="90%">
			<tr>
				<th width="50%">제목</th>
				<th class="hidden-xs">유저</th>
				<th class="hidden-xs">분류</th>
				<th class="hidden-xs">답변</th>
				<th class="hidden-xs">조회</th>
				<th class="hidden-xs">생성</th>
				<th class="hidden-xs">마지막 답변</th>
			</tr>
		{%- for post in posts -%}
			<tr class="{% if (post.votes_up - post.votes_down) <= -3 %}post-negative{% endif %}">
				<td align="left">

					{%- if post.sticked == "Y" -%}
						<span class="glyphicon glyphicon-pushpin"></span>&nbsp;
					{%- endif -%}
					{{- link_to('discussion/' ~ post.id ~ '/' ~ post.slug, post.title|e) -}}
					{%- if post.accepted_answer == "Y" -%}
						&nbsp;<span class="label label-success">해결</span>
					{%- else -%}
						{%- if post.canHaveBounty() -%}
							&nbsp;<span class="label label-info">현상금</span>
						{%- endif -%}
					{%- endif -%}

				</td>
				<td class="hidden-xs">
					{%- cache "post-users-" ~ post.id -%}
						{%- for id, user in post.getRecentUsers() -%}
						 	<a href="{{ url("user/" ~ id ~ "/" ~ user[0]) }}" title="{{ user[0] }}">
								<img src="https://secure.gravatar.com/avatar/{{ user[1] }}?s=24&amp;r=pg&amp;d=identicon" width="24" height="24" class="img-rounded">
							</a>
						{%- endfor -%}
					{%- endcache -%}
				</td>
				<td class="hidden-xs">
					<span class="category">{{ link_to('category/' ~ post.category.id ~ '/' ~ post.category.slug, post.category.name) }}</span>
				</td>
				<td class="hidden-xs" align="center">
					<span class="big-number">{% if post.number_replies > 0 %}{{ post.number_replies }}{%endif %}</span>
				</td>
				<td class="hidden-xs" align="center">
					<span class="big-number">{{ post.number_views }}</span>
				</td>
				<td class="hidden-xs">
					<span class="date">{{ post.getHumanCreatedAt() }}</span>
				</td>
				<td class="hidden-xs">
					<span class="date">{{ post.getHumanModifiedAt() }}</span>
				</td>
			</tr>
		{%- endfor -%}
		</table>
	</div>
</div>

<div class="container">
	<ul class="pager">
		{%- if offset > 0 -%}
			<li class="previous">{{ link_to(paginatorUri ~ '/' ~ (offset - 40), 'Prev') }}</li>
		{%- endif -%}

		{%- if totalPosts.count > 40 -%}
			<li class="next">{{ link_to(paginatorUri ~ '/' ~ (offset + 40), 'Next') }}</li>
		{%- endif -%}
	</ul>
</div>

{%- else -%}
	<div class="container">글이 없습니다.</div>
{%- endif -%}
