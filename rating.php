<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Rating System</title>
</head>
<body>
    <h1>Rate the Work</h1>
    <div class="star-rating">
        <span class="star" data-value="20%">&#9733;</span>
        <span class="star" data-value="40%">&#9733;</span>
        <span class="star" data-value="60%">&#9733;</span>
        <span class="star" data-value="80%">&#9733;</span>
        <span class="star" data-value="100%">&#9733;</span>
    </div>
    <p id="rating-value">Your rating: 0</p>
    <pre>


    </pre>
    <div class="review-section">
        <h3>Write a Review</h3>
        <textarea placeholder="Write your review here..."></textarea>
        <input type="submit" style="text-align: right;">
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 50px;
            }
    
            .star-rating {
                display: inline-block;
                font-size: 40px;
                color: #ccc;
                cursor: pointer;
            }
    
            .star-rating .star:hover,
            .star-rating .star.selected {
                color:  #c36217;
            }
            .review-section {
                margin-top: 20px;
            }
            .review-section textarea {
                margin: 0 10px;
                width: 50%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                resize: vertical;
                min-height: 100px;   
            }
            h1{
                text-align: left;
                color: #c36217;
            }
        </style>
<!-----------------js------------------------------>
    <script>
        const stars = document.querySelectorAll('.star');
        const ratingValue = document.getElementById('rating-value');
        let selectedRating = 0;

        stars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = this.getAttribute('data-value');
                ratingValue.textContent = `Your rating: ${selectedRating}`;

                // Reset all stars and highlight up to the selected one
                stars.forEach(s => s.classList.remove('selected'));
                for (let i = 0; i < selectedRating; i++) {
                    stars[i].classList.add('selected');
                }
            });
        });
    </script>
</body>
</html>