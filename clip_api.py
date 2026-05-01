from flask import Flask, request, jsonify
from gradio_client import Client, handle_file
import tempfile
import os

app = Flask(__name__)

# Initialize Hugging Face Space client
# Replace with your deployed Space URL
SPACE_URL = os.getenv('HUGGINGFACE_SPACE_URL', 'SAyedA345/Image_embedding_CLIP')
client = Client(SPACE_URL)

@app.route('/generate-embedding', methods=['POST'])
def generate_embedding():
    """
    Generate CLIP embedding for an uploaded image using Hugging Face Space.
    
    Expects:
        - image file in multipart/form-data
    
    Returns:
        JSON with embedding array or error message
    """
    try:
        if 'image' not in request.files:
            return jsonify({
                'success': False,
                'error': 'No image provided'
            }), 400
        
        image_file = request.files['image']
        
        # Save to temporary file
        with tempfile.NamedTemporaryFile(delete=False, suffix='.jpg') as tmp:
            image_file.save(tmp.name)
            tmp_path = tmp.name
        
        # Call Hugging Face Space
        result = client.predict(
            image=handle_file(tmp_path),
            api_name="/predict"
        )
        
        # Clean up temporary file
        os.unlink(tmp_path)
        
        # Parse result
        if isinstance(result, dict) and 'embedding' in result:
            return jsonify({
                'success': True,
                'embedding': result['embedding'],
                'embedding_dim': len(result['embedding']),
                'model': 'openai/clip-vit-base-patch32',
                'message': 'Embedding generated successfully via Hugging Face Space'
            })
        else:
            return jsonify({
                'success': False,
                'error': 'No embedding returned from Space',
                'result': str(result)
            }), 500
            
    except Exception as e:
        return jsonify({
            'success': False,
            'error': str(e)
        }), 500

@app.route('/health', methods=['GET'])
def health():
    """Health check endpoint"""
    return jsonify({
        'status': 'ok',
        'space_url': SPACE_URL,
        'message': 'CLIP Embedding API is running'
    })

if __name__ == '__main__':
    print("=" * 60)
    print("CLIP Embedding API Server")
    print("=" * 60)
    print(f"Using Hugging Face Space: {SPACE_URL}")
    print("Server starting on http://localhost:5000")
    print("=" * 60)
    app.run(host='0.0.0.0', port=5000, debug=True)
