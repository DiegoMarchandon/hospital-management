@extends('layouts.app')

@section('title', 'Upload Medical Document')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Upload Medical Document</h1>
        <p class="text-gray-600 mt-2">Securely upload your medical documents to S3 cloud storage</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form id="documentForm" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Drag & Drop Zone -->
            <div class="border-2 border-dashed border-blue-300 rounded-lg p-8 text-center hover:border-blue-500 transition cursor-pointer bg-blue-50"
                 id="dropZone">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-blue-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-8l-3.172-3.172a4 4 0 00-5.656 0L28 20m0 0l-3.172-3.172a4 4 0 00-5.656 0l-.172.172" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <p class="text-gray-700 font-medium mb-2">Drag and drop your document here</p>
                <p class="text-gray-500 text-sm mb-4">or</p>
                <label for="document" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer inline-block">
                    Browse Files
                </label>
                <input type="file" id="document" name="document" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                <p class="text-gray-500 text-xs mt-4">
                    Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max 10 MB)
                </p>
            </div>

            <!-- File Preview -->
            <div id="filePreview" class="hidden bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div id="fileIcon" class="text-3xl"></div>
                        <div>
                            <p id="fileName" class="font-medium text-gray-800"></p>
                            <p id="fileSize" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                    <button type="button" id="removeFile" class="text-red-600 hover:text-red-800">✕</button>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-gray-700 font-medium mb-2">Document Description (Optional)</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Add any notes or description about this document..."
                ></textarea>
            </div>

            <!-- Upload Progress -->
            <div id="uploadProgress" class="hidden">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-700">Uploading to S3...</span>
                    <span id="progressPercent" class="text-gray-700">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <!-- S3 Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Secure Cloud Storage</p>
                        <p class="text-sm text-blue-700 mt-1">Your medical documents are encrypted and stored securely on AWS S3 with enterprise-grade security.</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4">
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                    disabled
                >
                    Upload Document
                </button>
                <a 
                    href="{{ route('dashboard') }}" 
                    class="px-6 py-3 bg-gray-300 text-gray-800 font-medium rounded-lg hover:bg-gray-400 transition"
                >
                    Cancel
                </a>
            </div>

            <!-- Success Message -->
            <div id="successMessage" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-medium text-green-900">Document uploaded successfully!</p>
                        <p id="successText" class="text-sm text-green-700 mt-1"></p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="errorMessage" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-medium text-red-900">Upload failed</p>
                        <p id="errorText" class="text-sm text-red-700 mt-1"></p>
                    </div>
                </div>
            </div>
        </form>

        <!-- Recent Uploads -->
        <div class="mt-8 pt-8 border-t">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Your Recent Documents</h2>
            <p class="text-gray-600 text-sm mb-4">All documents are stored securely in AWS S3 and can be accessed anytime.</p>
            <div id="documentsList" class="space-y-3">
                <div class="text-center py-8 text-gray-500">
                    <p>No documents uploaded yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const documentInput = document.getElementById('document');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');
const fileIcon = document.getElementById('fileIcon');
const removeFileBtn = document.getElementById('removeFile');
const submitBtn = document.getElementById('submitBtn');
const uploadProgress = document.getElementById('uploadProgress');
const progressBar = document.getElementById('progressBar');
const progressPercent = document.getElementById('progressPercent');
const successMessage = document.getElementById('successMessage');
const errorMessage = document.getElementById('errorMessage');
const documentForm = document.getElementById('documentForm');

// Drag and drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-blue-500', 'bg-blue-100');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-blue-500', 'bg-blue-100');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-blue-500', 'bg-blue-100');
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        documentInput.files = files;
        displayFilePreview(files[0]);
    }
});

// File input change
documentInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        displayFilePreview(e.target.files[0]);
    }
});

// Display file preview
function displayFilePreview(file) {
    filePreview.classList.remove('hidden');
    fileName.textContent = file.name;
    fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    
    // Set icon based on file type
    const ext = file.name.split('.').pop().toLowerCase();
    const icons = {
        'pdf': '📄',
        'doc': '📝',
        'docx': '📝',
        'jpg': '🖼️',
        'jpeg': '🖼️',
        'png': '🖼️'
    };
    fileIcon.textContent = icons[ext] || '📎';
    
    submitBtn.disabled = false;
}

// Remove file
removeFileBtn.addEventListener('click', () => {
    documentInput.value = '';
    filePreview.classList.add('hidden');
    submitBtn.disabled = true;
});

// Form submission
documentForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(documentForm);
    
    uploadProgress.classList.remove('hidden');
    successMessage.classList.add('hidden');
    errorMessage.classList.add('hidden');
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("documents.upload") }}', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok) {
            successMessage.classList.remove('hidden');
            document.getElementById('successText').textContent = `Your file has been uploaded to S3 cloud storage: ${data.path}`;
            documentForm.reset();
            filePreview.classList.add('hidden');
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';
            
            setTimeout(() => {
                uploadProgress.classList.add('hidden');
                progressBar.style.width = '0%';
                progressPercent.textContent = '0%';
                submitBtn.disabled = true;
            }, 2000);
        } else {
            throw new Error(data.error || 'Upload failed');
        }
    } catch (error) {
        errorMessage.classList.remove('hidden');
        document.getElementById('errorText').textContent = error.message;
        uploadProgress.classList.add('hidden');
        progressBar.style.width = '0%';
        progressPercent.textContent = '0%';
        submitBtn.disabled = false;
    }
});

// Simulate progress
function simulateProgress() {
    const interval = setInterval(() => {
        let currentWidth = parseFloat(progressBar.style.width) || 0;
        if (currentWidth < 90) {
            currentWidth += Math.random() * 30;
            progressBar.style.width = currentWidth + '%';
            progressPercent.textContent = Math.round(currentWidth) + '%';
        } else {
            clearInterval(interval);
        }
    }, 200);
}
</script>
@endsection
