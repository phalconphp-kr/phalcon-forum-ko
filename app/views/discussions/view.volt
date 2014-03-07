{{- content() -}}

{{ flashSession.output() }}

{%- set currentUser = session.get('identity'), moderator = session.get('identity-moderator') -%}

{%- if (post.votes_up - post.votes_down) <= -3 -%}
	<div class="bs-callout bs-callout-danger">
		<h4>너무 많은 부정적 투표</h4>
		<p>이 게시물은 너무 많은 부정적 투표를 받았습니다. 아마도 잘못된 정보, 데이터, 스팸과 같은 안좋은 단어때문일것입니다.</p>
	</div>
{%- endif -%}

{%- if post.canHaveBounty() -%}
{%- set bounty = post.getBounty() -%}
<div class="bs-callout bs-callout-info">
	<h4>현상금이 가능합니다!</h4>
	{%- if bounty['type'] == "old" -%}
	<p>이 질문에 아직 아무런 답변도 없습니다.만약 이 질문에 답변을 하시고 질문자가 당신의 답변을 채택한다면 <span class="label label-info">+{{ bounty['value'] }}</span> 점수를 얻으실 것입니다.</p>
	{%- elseif bounty['type'] == "fast-reply" -%}
	<p>이 게시물은 최근에 제출되었습니다. 만약 이 질문에 가장 빨리 답변을 해주시고 질문자가 당신의 답변을 채택한다면 <span class="label label-info">+{{ bounty['value'] }}</span> 점수를 얻으실 것입니다.</p>
	{%- endif -%}
</div>
{%- endif -%}

<div class="container">

	<ol class="breadcrumb">
		<li>{{ link_to('', '첫화면') }}</a></li>
		<li>{{ link_to('category/' ~ post.category.id ~ '/' ~ post.category.slug, post.category.name) }}</a></li>
	</ol>

	<p>
		<div class="row table-title">
			<div class="col-md-8">
				<h1 class="{% if (post.votes_up - post.votes_down) <= -3 %}post-negative-h1{% endif %}">
					{{- post.title|e -}}
				</h1>
			</div>
			<div class="col-md-4">
				<table class="table-stats">
					<td>
						<label>생성됨</label><br>
						{{- post.getHumanCreatedAt() -}}
					</td>
					<td>
						<label>마지막 답변</label><br>
						{{- post.getHumanModifiedAt() ? post.getHumanModifiedAt() : "없음" -}}
					</td>
					<td>
						<label>답변</label><br>
						{{- post.number_replies -}}
					</td>
					<td>
						<label>조회수</label><br>
						{{- post.number_views -}}
					</td>
					<td>
						<label>투표수</label><br>
						{{- post.votes_up - post.votes_down -}}
					</td>
				</table>
			</div>
		</div>
	</p>

	<div class="discussion">
		<div class="row">
			<div class="col-md-1 small" align="center">
				<img src="https://secure.gravatar.com/avatar/{{ post.user.gravatar_id }}?s=48&amp;r=pg&amp;d=identicon" class="img-rounded" width="48" height="48"><br>
				<span>{{ link_to('user/' ~ post.user.id ~ '/' ~ post.user.login, post.user.name|e, 'class': 'user-moderator-' ~ post.user.moderator) }}</span><br>
				<span class="karma">{{ post.user.getHumanKarma() }}</span>
			</div>
			<div class="col-md-11 post-body{% if (post.votes_up - post.votes_down) <= -3 %} post-negative-body{% endif %}">
				<div class="posts-buttons" align="right">
					{% if post.edited_at > 0 %}
						<span class="action-date action-edit" data-id="{{ post.id }}" data-toggle="modal" data-target="#historyModal">
							<span>{{ post.getHumanEditedAt() }}</span> 수정됨
						</span><br/>
					{% endif %}
					<a name="C{{ post.id }}" href="#C{{ post.id }}">
						<span class="action-date">
							<span>{{ post.getHumanCreatedAt() }}</span>
						</span>
					</a>
				</div>
				<div class="post-content">
					{%- cache "post-body-" ~ post.id -%}
					{{- markdown.render(post.content|e) -}}
					{%- endcache -%}
				</div>
				<div class="posts-buttons" align="right">
					{%- if post.users_id == currentUser or moderator == 'Y' -%}
						{{ link_to('edit/discussion/' ~ post.id, '<span class="glyphicon glyphicon-pencil"></span>&nbsp;수정', "class": "btn btn-default btn-xs") }}
						{{ link_to('delete/discussion/' ~ post.id, '<span class="glyphicon glyphicon-remove"></span>&nbsp;삭제', "class": "btn btn-default btn-xs") }}&nbsp;
					{%- endif %}
					{%- if currentUser -%}
						<a href="#" onclick="return false" class="btn btn-danger btn-xs vote-post-down" data-id="{{ post.id }}">
							<span class="glyphicon glyphicon-thumbs-down"></span>
							{{ post.votes_down }}
						</a>
						<a href="#" onclick="return false" class="btn btn-success btn-xs vote-post-up" data-id="{{ post.id }}">
							<span class="glyphicon glyphicon-thumbs-up"></span>
							{{ post.votes_up }}
						</a>
					{%- else -%}
						<a href="#" onclick="return false" class="btn btn-danger btn-xs">
							<span class="glyphicon glyphicon-thumbs-down"></span>
							{{- post.votes_down -}}
						</a>
						<a href="#" onclick="return false" class="btn btn-success btn-xs">
							<span class="glyphicon glyphicon-thumbs-up"></span>
							{{- post.votes_up -}}
						</a>
					{%- endif -%}
				</div>
			</div>
		</div>

		{%- for reply in post.replies -%}
			<div class="row{% if (reply.votes_up - reply.votes_down) <= -3 %} reply-negative{% endif %}{% if (reply.votes_up - reply.votes_down) >= 4 %} reply-positive{% endif %}{% if reply.accepted == 'Y' %} reply-accepted{% endif %}">
				<div class="col-md-1 small" align="center">
					<img src="https://secure.gravatar.com/avatar/{{ reply.user.gravatar_id }}?s=48&amp;r=pg&amp;d=identicon" class="img-rounded"><br>
					<span>{{ link_to('user/' ~ reply.user.id ~ '/' ~ reply.user.login, reply.user.name|e, 'class': 'user-moderator-' ~ reply.user.moderator) }}</span><br>
					<span class="karma">{{ reply.user.getHumanKarma() }}</span>
					{%- if reply.accepted == 'Y' -%}
						<div class="accepted-reply">
							<span class="glyphicon glyphicon-ok"></span>
							채택
						</div>
					{%- endif -%}
				</div>
				<div class="col-md-11">
					{%- if reply.in_reply_to_id > 0 -%}
						{%- set inReplyTo = reply.postReplyTo -%}
						{%- if inReplyTo -%}
						<div class="in-reply-to">
							<a href="#C{{ reply.in_reply_to_id }}"><span class="glyphicon glyphicon-chevron-up"></span> in reply to
								<img src="https://secure.gravatar.com/avatar/{{ inReplyTo.user.gravatar_id }}?s=24&amp;r=pg&amp;d=identicon" class="img-rounded" width="24" height="24"> {{ inReplyTo.user.name }}</a>
						</div>
						{%- endif -%}
					{%- endif -%}
					<div class="posts-buttons" align="right">
						{%- if reply.edited_at > 0 -%}
							<span class="action-date action-reply-edit" data-id="{{ reply.id }}" data-toggle="modal" data-target="#historyModal">
								<span>{{ reply.getHumanEditedAt() }}</span> 수정됨
							</span><br/>
						{%- endif -%}
						<a name="C{{ reply.id }}" href="#C{{ reply.id }}">
							<span class="action-date">
								<span>{{ reply.getHumanCreatedAt() }}</span>
							</span>
						</a>
					</div>
					<div class="post-content">
						{%- cache "reply-body-" ~ reply.id -%}
						{{- markdown.render(reply.content|e) -}}
						{%- endcache -%}
					</div>
					<div class="posts-buttons" align="right">
						{%- if currentUser == post.users_id or moderator == 'Y' -%}
							<br>
							{%- if post.accepted_answer != 'Y' -%}
								<a class="btn btn-default btn-xs reply-accept" data-id="{{ reply.id }}">
									<span class="glyphicon glyphicon-ok"></span>&nbsp;채택
								</a>&nbsp;
							{%- endif -%}
						{%- endif -%}
						{%- if reply.users_id == currentUser or moderator == 'Y' -%}
							<a class="btn btn-default btn-xs reply-edit" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-pencil"></span>&nbsp;수정
							</a>
							<a class="btn btn-default btn-xs reply-remove" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-remove"></span>&nbsp;삭제
							</a>&nbsp;
						{%- endif -%}
						{%- if currentUser -%}
							{%- if reply.users_id != currentUser -%}
							<a class="btn btn-default btn-xs reply-reply" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-share-alt"></span>&nbsp;답변 
							</a>&nbsp;
							{%- endif -%}
							<a href="#" onclick="return false" class="btn btn-danger btn-xs vote-reply-down" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-thumbs-down"></span>
								{{ reply.votes_down }}
							</a>
							<a href="#" onclick="return false" class="btn btn-success btn-xs vote-reply-up" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-thumbs-up"></span>
								{{ reply.votes_up }}
							</a>
						{%- else -%}
							<a href="#" onclick="return false" class="btn btn-danger btn-xs vote-login" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-thumbs-down"></span>
								{{ reply.votes_down }}
							</a>
							<a href="#" onclick="return false" class="btn btn-success btn-xs vote-login" data-id="{{ reply.id }}">
								<span class="glyphicon glyphicon-thumbs-up"></span>
								{{ reply.votes_up }}
							</a>
						{%- endif -%}
					</div>
				</div>
			</div>
			{%- endfor -%}

			<div class="row">
			{%- if currentUser -%}
				<div class="col-md-1 small" align="center">
					<img src="https://secure.gravatar.com/avatar/{{ session.get('identity-gravatar') }}?s=48&amp;r=pg&amp;d=identicon" class="img-rounded" width="48" height="48"><br>
					<span>{{ link_to('', 'You') }}</span>
				</div>
				<div class="col-md-11">

					<ul class="nav nav-tabs preview-nav">
						<li class="active"><a href="#" onclick="return false">댓글</a></li>
						<li><a href="#" onclick="return false">미리보기</a></li>
						<li class="pull-right">{{ link_to('help/markdown', '도움말', 'parent': '_blank') }}</li>
					</ul>

					<form method="post" autocomplete="off" role="form">
						<p>
							<div id="comment-box">
								{{- hidden_field('id', 'value': post.id) -}}
								{{- text_area("content", "rows": 5, "class": "form-control") -}}
							</div>
							<div id="preview-box" style="display:none"></div>
						</p>
						<p>
							<div class="pull-left">
								{{- link_to('', '게시판으로 돌아가기') -}}
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-success">댓글 달기</button>
							</div>
						</p>
					</form>
				</div>
			{%- else -%}
				<div class="col-md-1 small" align="center"></div>
				<div class="col-md-11">
					<div class="pull-left">
						{{- link_to('', '게시판으로 돌아가기') -}}
					</div>
					<div class="pull-right">
						{{- link_to('login/oauth/authorize', '로그인하여 댓글달기', 'class': 'btn btn-info') -}}
					</div>
				</div>
			{%- endif -%}
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="historyModalLabel">역사</h4>
			</div>
			<div class="modal-body" id="historyBody">
				Loading...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header alert-danger">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="errorModalLabel">에러</h4>
			</div>
			<div class="modal-body" id="errorBody">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
			</div>
		</div>
	</div>
</div>

{%- if currentUser -%}
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" autocomplete="off" role="form">
			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="replyModalLabel">답변 달기</h4>
				</div>

				<div class="modal-body" id="errorBody">
					<ul class="nav nav-tabs preview-nav">
						<li class="active"><a href="#" onclick="return false">댓글</a></li>
						<li><a href="#" onclick="return false">미리보기</a></li>
						<li class="pull-right">{{ link_to('help/markdown', '도움말', 'parent': '_blank') }}</li>
					</ul>
					<p>
						<div id="reply-comment-box">
							{{- hidden_field('id', 'value': post.id) -}}
							{{- hidden_field('reply-id') -}}
							<div id="comment-textarea"></div>
						</div>
						<div id="preview-box" style="display:none"></div>
					</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
					<input type="submit" class="btn btn-success" value="Add Reply"/>
				</div>
			</div>
		</form>
	</div>
</div>
{% endif %}