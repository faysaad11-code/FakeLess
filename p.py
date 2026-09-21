import sys
import json
import os
import warnings
warnings.filterwarnings('ignore')

import torch
import torch.nn as nn
from PIL import Image
from torchvision import transforms, models

BASE_DIR   = os.path.dirname(os.path.abspath(_file_))
MODEL_PATH = os.path.join(BASE_DIR, "best_model8.pth")
DEVICE     = torch.device("cuda" if torch.cuda.is_available() else "cpu")

model = models.efficientnet_b4(weights=None)
model.classifier[1] = nn.Linear(model.classifier[1].in_features, 2)
model.load_state_dict(torch.load(MODEL_PATH, map_location=DEVICE, weights_only=True))
model = model.to(DEVICE)
model.eval()

transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize([0.485, 0.456, 0.406],
                         [0.229, 0.224, 0.225]),
])

IDX_TO_CLASS = {0: "FAKE", 1: "REAL"}

def predict_image(image_path):
    img    = Image.open(image_path).convert("RGB")
    tensor = transform(img).unsqueeze(0).to(DEVICE)

    with torch.no_grad():
        logits = model(tensor)
        probs  = torch.softmax(logits, dim=1)[0]
        pred   = torch.argmax(probs).item()

    return {
        "prediction": IDX_TO_CLASS[pred],
        "probabilities": {
            IDX_TO_CLASS[i]: float(probs[i].item()) for i in range(len(probs))
        }
    }

if _name_ == "_main_":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No image path provided"}, ensure_ascii=False))
        sys.exit(1)

    try:
        result = predict_image(sys.argv[1])
        print(json.dumps(result, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({"error": str(e)}, ensure_ascii=False))
        sys.exit(1)