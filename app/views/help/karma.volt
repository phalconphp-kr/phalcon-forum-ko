
<div class="container help">

	<ol class="breadcrumb">
		<li>{{ link_to('', '첫화면') }}</a></li>
		<li>{{ link_to('help', '도움말') }}</a></li>
	</ol>

	<h1>카르마/평판</h1>

	<p>
		카르마와 평판은 기여, 협력과 포럼에 참여하기 위한 사용자 보상 점수 시스템입니다.
		거의 모든 포럼 활동에 대한 점수를 계산하여 카르마는 커뮤니티를 활성화하여 가장 좋거나 나쁜 공헌을 식별합니다.
		이 문서는 각 활동에 주어진 점수가 얼마나 되는지를 설명합니다:
	</p>

	<div align="center">
		<table class="table table-stripped">
			<thead>
				<tr>
					<td><h3>일반</h3></td>
				</tr>
			</thead>
			<thead>
				<tr>
					<th>활동</th>
					<th>점수</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>포럼에 가입</td>
					<td><span class="label label-success">+45</span></th>
				</tr>
				<tr>
					<td>포럼에 로그인</td>
					<td><span class="label label-success">+5</span></th>
				</tr>
				<tr>
					<td>다른이의 게시물을 조회</td>
					<td><span class="label label-success">+2</span></th>
				</tr>
				<tr>
					<td>당신의 게시물을 조회</td>
					<td><span class="label label-success">+1</span></th>
				</tr>
				<tr>
					<td>새로운 게시물을 제출</td>
					<td><span class="label label-success">+10</span></th>
				</tr>
				<tr>
					<td>다른이의 게시물에 답변</td>
					<td><span class="label label-success">+15</span></th>
				</tr>
				<tr>
					<td>당신의 게시물에 다른이가 답변</td>
					<td><span class="label label-success">+5</span></th>
				</tr>
				<tr>
					<td>당신의 답변을 다른이가 채택</td>
					<td><span class="label label-success">30 + abs(user_karma - your_karma) / 1000</span></th>
				</tr>
				<tr>
					<td>채택된 답변에 다른이도 채택</td>
					<td><span class="label label-success">+10</span></th>
				</tr>
				<tr>
					<td>다른이의 게시물에 어떤것이든 투표</td>
					<td><span class="label label-success">+10</span></th>
				</tr>
				<tr>
					<td>당신의 게시물에 다른이가 긍정적 투표</td>
					<td><span class="label label-success">5 + abs(user_karma - your_karma) / 1000</span></th>
				</tr>
				<tr>
					<td>당신의 게시물에 다른이가 부정적 투표</td>
					<td><span class="label label-danger">-(5 + abs(user_karma - your_karma) / 1000)</span></th>
				</tr>
				<tr>
					<td>당신의 댓글에 원저자가 긍정적 투표</td>
					<td><span class="label label-success">15 + abs(user_karma - your_karma) / 1000</span></th>
				</tr>
				<tr>
					<td>당신의 댓글에 원저자가 부정적 투표</td>
					<td><span class="label label-danger">-(15 + abs(user_karma - your_karma) / 1000)</span></th>
				</tr>
				<tr>
					<td>당신의 댓글에 다른이가 긍정적 투표</td>
					<td><span class="label label-success">10 + abs(user_karma - your_karma) / 1000</span></th>
				</tr>
				<tr>
					<td>당신의 댓글에 다른이가 부정적 투표</td>
					<td><span class="label label-danger">-(10 + abs(user_karma - your_karma) / 1000)</span></th>
				</tr>
				<tr>
					<td>게시물 삭제</td>
					<td><span class="label label-danger">-15</span></th>
				</tr>
				<tr>
					<td>답변 삭제</td>
					<td><span class="label label-danger">-15</span></th>
				</tr>
			</tbody>
		</table>
	</div>

	<div align="center">
		<table class="table table-stripped">
			<thead>
				<tr>
					<td><h3>관리자를 위한 점수</h3></td>
				</tr>
			</thead>
			<thead>
				<tr>
					<th>활동</th>
					<th>점수</th>
				</tr>
				<tr>
					<td>게시물이나 댓글을 개선하거나 올바른 분류로 이동</td>
					<td><span class="label label-success">+25</span></th>
				</tr>
				<tr>
					<td>공격적이거나 스팸성 게시물이나 댓글 삭제</td>
					<td><span class="label label-success">+10</span></th>
				</tr>
			</thead>
		</table>
	</div>

	<hr>

	<h3>카르마/평판 장점</h3>
	<ul>
		<li>커뮤니티에서 존경받습니다</li>
		<li>당신의 공헌이 영향을 끼치며 기록에 남습니다. </li>
		<li>당신의 게시물과 답변은 좀 더 보기 좋게 됩니다</li>
		<li>잠재적으로 관리자로 승격될수 있습니다</li>
	</ul>

</div>