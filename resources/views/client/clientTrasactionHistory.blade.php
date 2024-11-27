<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
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

        .img-person {
            width: 60px; 
            height: 60px; 
            border-radius: 50%; 
            margin-bottom: -10px; 
            border: 4px solid rgb(3, 170, 67);
        }

        .custom-search-button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .custom-search-button:hover {
            background-color: #45a049;
        }

        /* Card Styles */
        .card {
            width: 250px;
            margin: 10px;
            border-radius: 8px;
            overflow: hidden;
            background-color: #222;
            color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            color: #222;
            font-size: 18px;
            font-weight: bold;
        }

        .progress-bar {
            height: 5px;
            background-color: #45a049;
            width: 100%;
            transition: width 0.5s ease;
        }

        .progress-container {
            background-color: #444;
            height: 5px;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-text {
            position: absolute;
            right: 15px;
            top: 160px;
            font-size: 14px;
            color: #222;
        }

        /* Flexbox for Cards */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

    </style>
</head>
<body>
    <div class="app-bar">
        <div class="search-container">
            <form class="form-inline" action="/search" method="GET">
                <input type="text" name="query" class="form-control mr-sm-2" placeholder="Search" aria-label="Search" style="margin-left:1100px">
                <button class="btn custom-search-button" type="submit">
                    <i class="fas fa-search" style="font-size: 20px;"></i> Search
                </button>
            </form>
        </div>
    </div>

    @include('components.clientDrawer')

    <div style="display: flex; justify-content: center; margin-top: 40px; width:1000px; margin-left:400px">
    <div class="container">
        <h2>My Classes</h2>
        @if ($tasks->isEmpty())
            <div class="alert alert-info text-center">
                No tasks history available.
            </div>
        @else
            <table class="table mt-4" style="background-color: rgba(128, 128, 128, 0.1); border: 2px solid black; border-radius: 8px; overflow: hidden; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);">
                <thead style="background-color: rgba(128, 128, 128, 0.2); border-bottom: 2px solid black;">
                    <tr>
                        <th style="border-right: 1px solid black;">ID</th>
                        <th style="border-right: 1px solid black;">Transaction ID</th>
                        <th style="border-right: 1px solid black;">Transaction Name</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $list)
                        <tr style="background-color: white; border-top: 1px solid black; cursor: pointer;"
                            onmouseover="this.style.backgroundColor='black'; this.style.color='limegreen';"
                            onmouseout="this.style.backgroundColor='rgba(128, 128, 128, 0.1)'; this.style.color='';">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$list->transaction_id}}</td>
                            <td style="border-right: 1px solid black;">{{ $list->name }}</td>
                            <td>
                                <form action="{{ route('client.clientTrackDocument', ['task_id' => $list->task_id,'transaction_id'=>$list->transaction_id]) }}" method="get" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background-color: #00b894; color: black;">View Message</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const images = [
                'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcSUmrliao3jjDaQdeMjX5_ezS6F6eBqZP0A&s',
                'https://i.ebayimg.com/images/g/fj0AAOSwxZJkLddI/s-l1200.jpg',
                'https://hentaiheart.com/wp-content/uploads/2023/08/comedy-hentai-anime-joshi-luck.jpg',
                'https://ih1.redbubble.net/image.4517307491.9725/raf,360x360,075,t,fafafa:ca443f4786.jpg',
                'https://rukminim2.flixcart.com/image/850/1000/xif0q/elder-halloween-costume/r/y/u/l-realistic-scary-human-face-mask-funny-costume-party-mask-for-original-imaghj5xxwd9mhmh.jpeg?q=90&crop=false',
                'https://meme-dev-v2.s3.eu-west-1.amazonaws.com/uploads/2022/11/25/1669338080865-3607461/IMG_20201123_025741.jpg.jpg',
                'https://image.winudf.com/v2/image1/Y29tLmthbmZvLmZ1bm55cHJvZmlsZXBpY3R1cmVfc2NyZWVuXzJfMTY3NzM3MzY0Nl8wMDg/screen-2.jpg?fakeurl=1&type=.jpg',
                'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRj1hsbdFpX-_53EQpzkMTozDpQjXStMkeo6JeAcTZYVNadFbpUw9Dua7ocyJS4O2RPwm0&usqp=CAU',
                'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1ouWskod9W33FF_u766j5z-NxJKTJFnz5bcZJ1yst3XOgMOprsEek2j5bNg07vNpxhHw&usqp=CAU',
                'https://mrwallpaper.com/images/hd/funny-anime-goku-meme-face-ty7gabr1q0lvytpd.jpg',
                'https://cdn.myanimelist.net/s/common/uploaded_files/1482966849-4adc013b44561dbfd625e8d81364963c.jpeg',
                'https://pbs.twimg.com/media/EW3PoI1WoAIc0Rd.jpg',
                'https://ih1.redbubble.net/image.4517307491.9725/raf,360x360,075,t,fafafa:ca443f4786.jpg',
                'https://media.tenor.com/AzD2jWG_vVwAAAAM/confused-barakamon.gif',
            ];

            const cardImages = document.querySelectorAll('.random-image');

            cardImages.forEach(image => {
                const randomIndex = Math.floor(Math.random() * images.length);
                image.src = images[randomIndex];
            });
        });
    </script>
</body>
</html>
