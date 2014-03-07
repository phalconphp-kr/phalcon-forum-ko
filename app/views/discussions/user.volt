
<hr>

<div align="center" class="container">
	<div class="user-profile">
		<table align="center">
			<tr>
				<td class="small hidden-xs" valign="top">
					<img src="https://secure.gravatar.com/avatar/{{ user.gravatar_id }}?s=64&amp;r=pg&amp;d=identicon" class="img-rounded" width="64" height="64">
				</td>
				<td align="left" valign="top">
					<h1>{{ user.name|e }}</h1>
					<span class="login">{{ user.login }}</span><br>
					<p>
						<span>가입함 <b>{{ date('M d/Y', user.created_at) }}</b></span><br>
						<span>게시물 <b>{{ numberPosts }}</b></span> / <span>답변 <b>{{ numberReplies }}</b></span><br>
						<span>평판 <b>{{ user.karma }}</b></span><br>
						<span>평판 순위 <b>{{ ranking }}</b>번째 (전체 <b>{{ total_ranking }}</b>)</span><br>
						<a href="https://github.com/{{ user.login }}">Github 프로필</a>
					</p>
					<p>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#">최근 활동</a><li>
						</ul>
					</p>
					<p>
						<table class="table table-striped">
						{% for activity in activities %}
							<tr><td>
								<span class="date">{{ activity.getHumanCreatedAt() }}</span>
								{% if activity.type == 'U' %}
								포럼에 가입하셨습니다.
								{% elseif activity.type == 'P' %}
								has posted {{ link_to('discussion/' ~ activity.post.id ~ '/' ~ activity.post.slug, activity.post.title|e) }}
								{% elseif activity.type == 'C' %}
								has commented in {{ link_to('discussion/' ~ activity.post.id ~ '/' ~ activity.post.slug, activity.post.title|e) }}
								{% endif %}
							</td></tr>
						{% endfor %}
						</table>
					</p>
				</td>
			</tr>
		</table>
	</div>
</div>