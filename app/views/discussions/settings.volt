{{ content() }}

<hr>

<div align="center" class="container">
	<div class="user-profile">
		<table align="center">
			<tr>
				<td class="small hidden-xs" valign="top">
					<img src="https://secure.gravatar.com/avatar/{{ user.gravatar_id }}?s=64&amp;r=pg&amp;d=identicon" class="img-rounded"
					width="64" height="64">
				</td>
				<td align="left" valign="top">
					<h1>{{ user.name|e }}</h1>
					<p>
						<span>가입일 <b>{{ date('M d/Y', user.created_at) }}</b></span><br>
						<span>게시물 <b>{{ numberPosts }}</b></span> / <span>replies <b>{{ numberReplies }}</b></span><br>
						<span>평판 <b>{{ user.karma }}</b></span><br>
						<span>투표 가능 <b>{{ user.votes }}</b></span><br>
						<span>투표 점수 <b>{{ user.votes_points }}/50</b></span><br>
					</p>
					<hr>
					<p>
						<ul class="nav nav-tabs">
							<li class="active"><a href="#">설정</a><li>
						</ul>
					</p>
					<p>
						<div class="tab-content">
							<div class="tab-pane active" id="settings">
								<form method="post" role="form">
									<div class="form-group">
										<label for="timezone">시간대</label>
										{{ select_static('timezone', timezones, 'class': 'form-control') }}
									</div>
									<div class="form-group">
										<label for="notifications">이메일 알림</label>
										{{ select_static('notifications', [
											'N': '절대안받음',
											'Y': '항상받음',
											'P': '누군가 내 의견에 답변을 달았을 경우에만'
										], 'class': 'form-control') }}
									</div>
									<div class="form-group">
										<a href="https://en.gravatar.com/">당신의 아바타는 Gravatar 에서 바꾸세요</a>
									</div>
									<div class="form-group">
										<input type="submit" class="btn btn-success" value="Save"/>
									</div>
								</form>
							</div>
						</div>
					</p>
				</td>
			</tr>
		</table>
	</div>
</div>
