{{ content() }}

<div class="container start-discussion">

	<div align="left">
		<h1>글쓰기</h1>
	</div>

	<div class="row">
		<div class="col-md-1 remove-image hidden-xs" align="right">
			<img src="https://secure.gravatar.com/avatar/{{ session.get('identity-gravatar') }}?s=48&amp;r=pg&amp;d=identicon" class="img-rounded">
		</div>
		<div class="col-md-11">

			<div class="bs-callout bs-callout-info">
				<h4>새글 작성하기</h4>
				<p>잠깐만! 여기에 글을 남기시기전에 <a href="https://github.com/phalcon/cphalcon/issues">Github</a> 에서 비슷한 이슈를 확인하고 버그와 문제를 공유해주시면 우리에게 큰 도움이 됩니다.</p>
			</div>

			<form method="post" autocomplete="off" role="form">

			  <div class="form-group">
				<label>제목</label>
				{{ text_field("title", "placeholder": "제목", "class": "form-control") }}
			  </div>

			  <div class="form-group">
				<label>분류</label>
				{{ select("categoryId", categories, 'using': ['id', 'name'], 'useEmpty': true, 'emptyText': '분류를 선택해주세요...', "class": "form-control") }}
			  </div>

			  <div class="form-group">

				<ul class="nav nav-tabs preview-nav">
					<li class="active"><a href="#" onclick="return false">쓰기</a></li>
					<li><a href="#" onclick="return false">미리보기</a></li>
					<li class="pull-right">{{ link_to('help/markdown', '도움', 'parent': '_new') }}</li>
				</ul>

				<div id="comment-box">
					<div class="form-group">
						{{ text_area("content", "rows": 15, "placeholder": "이곳에 내용 적기", "class": "form-control") }}
					</div>
				</div>
				<div id="preview-box" style="display:none"></div>
			  </div>

			  <p>
				<div class="pull-left">
					{{ link_to('', '게시판으로 돌아가기') }}
				</div>
				<div class="pull-right">
					<button type="submit" class="btn btn-sm btn-success">제출</button>
				</div>
			  </p>

			</form>
		</div>
	</div>
</div>

