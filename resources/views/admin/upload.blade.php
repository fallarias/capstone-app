

<h1>HELLO</h1>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif
@if (session('error'))
    <p>{{ session('error') }}</p>
@endif

<style>
    .hidden { display: none; }
</style>

<label><input type="checkbox" id="checkbox1">Upload File</label>
<label><input type="checkbox" id="checkbox2">Upload Folder</label>

    <div id="form1" class="hidden">
        <h2>Upload File</h2>
        <form action="{{ route('admin.upload_file') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- <label for="">Filename:</label>
            <input type="text" placeholder="File Name" name="filename"><br> -->
            <label for="">File</label>
            <input type="file" name="filepath[]" multiple><br>
            <input type="submit">
        </form>
    </div>

    <div id="form2" class="hidden">
        <h2>Upload Folder</h2>
        <form action="{{ route('admin.upload_file') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="">Filename:</label>
            <input type="text" placeholder="File Name" name="filename"><br>
            <label for="">File</label>
            <input type="file" name="filepath"><br>
            <input type="submit">
        </form> 
    </div>

    <script>
        document.getElementById('checkbox1').addEventListener('change', function() {
            document.getElementById('form1').classList.toggle('hidden', !this.checked);
        });

        document.getElementById('checkbox2').addEventListener('change', function() {
            document.getElementById('form2').classList.toggle('hidden', !this.checked);
        });
    </script>
