{{ content() }}

<div class="start-discussion container">

	<ol class="breadcrumb">
		<li>{{ link_to('', '첫화면') }}</a></li>
		<li>{{ link_to('category/' ~ post.category.id ~ '/' ~ post.category.slug, post.category.name) }}</a></li>
	</ol>

	<div align="left">
		<h1>게시물 수정: {{ post.title|e }}</h1>
	</div>

	<div class="row">
		<div class="col-md-1 remove-image" align="right">
			<img src="https://secure.gravatar.com/avatar/{{ session.get('identity-gravatar') }}?s=48&amp;r=pg&amp;d=identicon" class="img-rounded">
		</div>
		<div class="col-md-10">
			<form method="post" autocomplete="off" role="form">

				<div class="form-group">
					{{ hidden_field("id") }}
				</div>

				<div class="form-group">
					{{ text_field("title", "placeholder": "제목", "class": "form-control") }}
				</div>

				<div class="form-group">
					{{ select("categoryId", categories, 'using': ['id', 'name'], "class": "form-control") }}
				</div>

				<p>
					<ul class="nav nav-tabs preview-nav">
						<li class="active"><a href="#" onclick="return false">쓰기</a></li>
						<li><a href="#" onclick="return false">미리보기</a></li>
						<li class="pull-right">{{ link_to('help/markdown', '도움말', 'parent': '_blank') }}</li>
					</ul>

					<div id="comment-box">
						{{ text_area("content", "rows": 15, "placeholder": "이곳에 내용 적기", "class": "form-control") }}
					</div>
					<div id="preview-box" style="display:none"></div>
				</p>

				<p>
					<div class="pull-left">
						{{ link_to('discussion/' ~ post.id ~ '/' ~ post.slug , '취소') }}
					</div>
					<div class="pull-right">
						<button type="submit" class="btn btn-success">저장</button>
					</div>
			  	</p>

			</form>
		</div>
	</div>
</div>
