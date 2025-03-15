

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    
    <style>
        /* Custom App Bar Styles */
        .app-bar {
            background-color: #18392B;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 96px;
            padding-right: 20px;
            padding-left: 80px;
        }

        .app-bar .title {
            font-size: 74px;
            font-weight: bold;
            margin: 0;
        }

        .app-bar .nav-links {
            display: flex;
            gap: 50px;
        }

        .app-bar .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: -5px;
        }

        .app-bar .nav-links a:hover {
            text-decoration: underline;
        }

        /* Optional: Styles for Main Content */
        .container {
                margin-top: 20px;
                padding: 20px;
        }

        .img-person{
            width: 60px; 
            height: 60px; 
            border-radius: 50%; 
            margin-bottom: -10px; 
            border: 4px solid rgb(3, 170, 67);
        }

        .custom-search-button {
            background-color: #4CAF50; /* Change to your desired color */
            color: white; /* Text color */
            font-size: 16px; /* Adjust font size */
            padding: 10px 20px; /* Adjust padding for button size */
            border: none; /* Remove border */
            border-radius: 4px; /* Optional: rounded corners */
            cursor: pointer; /* Change cursor on hover */
        }

        .custom-search-button:hover {
            background-color: #45a049; /* Darken color on hover */
        }

        /* Optional: Styles for Main Content */
        .container {
            margin-top: 20px;
            margin-left: 100px;
            padding: 20px;
            max-width: 1400px; /* Set max-width to make it wider */
            width: 100%; /* Full width */
        }

        .dashboard-container {
            display: flex;
            gap: 10px; /* Reduced gap to make the layout more compact */
            margin-top: 20px;
            flex-wrap: wrap;
            margin-left: 130px;
        }

        .dashboard-card {
            width:300px; /* Adjusted width to fit four cards within 300px */
            padding: 10px; /* Reduced padding */
            margin-left: 20px;
            text-align: start;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card1 {
            width:955px; /* Adjusted width to fit four cards within 300px */
            padding: 10px; /* Reduced padding */
            margin-left: 150px;
            text-align: start;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            margin-top: -80px;
            text-align: start;
            margin-left: 150px;
        }

        /* Adjust font size in cards to keep content readable */
        .dashboard-card h1 {
            font-size: 26px;
            color: #00b894;
        }

        .dashboard-card p {
            font-size: 12px;
        }



                /* Styling for Review Cards */
        .review-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: -10px;
        }

        .review-card {
            width: 300px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .review-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 4px solid #03AA43;
            object-fit: cover;
        }

        .review-text {
            font-size: 14px;
            color: #555;
            margin: 10px 0;
        }

        .review-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }

        .review-role {
            font-size: 14px;
            color: gray;
        }

        .star-rating {
            color: #f5c518;
            font-size: 18px;
        }


        .star-rating i {
            color: gray;
            cursor: pointer;
        }

        /* Yellow stars when selected */
        .star-rating i.active {
            color:rgb(55, 242, 96);
        }


        .svrateButton {
            background-color: #00b894;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 280px;
            height: 50px;
        }

        .svrateButton:hover {
            background-color:rgb(58, 194, 83); /* Darker green */
            transform: scale(1.1); /* Slightly enlarges the button */
        }


        .ccrateButton {
            background-color: #00b894;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-left: 780px;
            width: 150px;
            margin-top: 280px;
        }

        .ccrateButton:hover {
            background-color:rgb(158, 44, 44); /* Darker green */
            transform: scale(1.1); /* Slightly enlarges the button */
        }

        .button-container {
            display: flex;
            justify-content: center; /* Aligns buttons horizontally in the center */
            gap: 20px; /* Adds space between buttons */
            margin-top: 20px; /* Adjust top spacing */
        }

    </style>
</head>
<body>


    <div class="app-bar">

    </div>




    <!-- SweetAlert Scripts -->
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: @json($errors->first()),
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Great...',
                text: @json(session('success')),
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ url("client/home") }}';
                }
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            })
        </script>
    @endif

    @include('components.clientDrawer')

    <!-- Main Content -->
    <!-- Main Content -->
    <div style="display: flex; justify-content: center; margin-top: 40px;margin-left: 150px; max-width:1400px;">
    <div class="container">
        <form action="{{ route('client.clientReview') }}" method="POST">
            @csrf
            <div class="review-container">
                @foreach ($transaction as $list)
                    <div class="review-card" data-staff-id="{{ $list->staff->id }}">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="Reviewer 1">
                        <p class="review-name">{{ $list->staff->firstname }} {{ $list->staff->lastname }}</p>
                        <p class="review-role">{{ $list->staff->department }}</p>

                        <!-- Star Rating -->
                        <div class="star-rating">
                            <i class="fas fa-star" data-index="1"></i>
                            <i class="fas fa-star" data-index="2"></i>
                            <i class="fas fa-star" data-index="3"></i>
                            <i class="fas fa-star" data-index="4"></i>
                            <i class="fas fa-star" data-index="5"></i>
                        </div>

                        <!-- Hidden input for storing rating -->
                        <input type="hidden" name="ratings[{{ $list->staff->user_id }}]" class="rating-value" value="0">
                        <input type="hidden" name="staff_ids[]" value="{{ $list->staff->user_id }}">
                        <input type="hidden" name="trans_ids[]" value="{{ $list->transaction_id }}">
                    </div>
                @endforeach
            </div>
            <div class="button-container">
                <button type="button" class="ccrateButton" onclick="goBack()">Cancel</button>
                <button type="submit" class="svrateButton">Save All Ratings</button>
            </div>
        </form>
    </div>
</div>



<script>
    $(document).ready(function() {
        $(".review-card .star-rating i").click(function() {
            var index = $(this).data("index");
            var parent = $(this).closest(".review-card");

            // Remove active class from stars within this review card
            parent.find(".star-rating i").removeClass("active");

            // Add active class (yellow) to clicked star and all previous stars
            parent.find(".star-rating i").each(function() {
                if ($(this).data("index") <= index) {
                    $(this).addClass("active");
                }
            });

            // Store selected rating in the hidden input field
            parent.find(".rating-value").val(index);
        });

        $(".save-rating").click(function(event) {
            var parent = $(this).closest(".review-card");
            var rating = parent.find(".rating-value").val();
            
        });
    });
</script>

<script>
    function goBack() {
        window.history.back();
    }
</script>




</body>
</html>


