<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1>Articles</h1>
    <div id="article-container">
        <!-- Articles will be loaded here -->
    </div>
    <button id="load-more" class="btn btn-primary mt-4">Load More</button>
</div>

<script>
$(document).ready(function() {
    var skip = 0;
    loadArticles(skip);

    $('#load-more').click(function() {
        skip += 5;
        loadArticles(skip);
    });

    function loadArticles(skip) {
        $.ajax({
            url: '{{ route("articles.loadMore") }}',
            type: 'POST',
            data: {
                skip: skip,
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                if (data.length == 0) {
                    $('#load-more').hide();
                }
                $.each(data, function(index, article) {
                    $('#article-container').append('<div class="card mt-3"><div class="card-body"><h5 class="card-title">' + article.title + '</h5><p class="card-text">' + article.body + '</p></div></div>');
                });
            }
        });
    }
});
</script>
</body>
</html>
