<?php
$tutorial_title = 'Artificial Intelligence';
$tutorial_slug  = 'ai';
$quiz_slug      = 'ai';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Artificial Intelligence (AI) is the simulation of human intelligence processes by computer systems — including learning, reasoning, problem-solving, perception, and language understanding. Once confined to research labs, AI has exploded into mainstream technology through breakthroughs in deep learning, transformer models, and massive compute availability. Understanding AI fundamentals is now essential for every software engineer.</p>',
        'concepts' => [
            'AI, ML, DL: the nested relationship between artificial intelligence, machine learning, and deep learning',
            'Rule-based AI vs. learning-based AI: expert systems vs. statistical models',
            'Supervised, unsupervised, and reinforcement learning paradigms',
            'Training data, features, labels, model, inference',
            'Overfitting and underfitting: the bias-variance tradeoff',
            'The AI development cycle: data → model → evaluate → deploy → monitor',
            'Key AI applications: vision, NLP, speech, recommendation, anomaly detection',
        ],
        'code' => [
            'title'   => 'First ML model with scikit-learn',
            'lang'    => 'python',
            'content' =>
"from sklearn.datasets        import load_iris
from sklearn.model_selection import train_test_split
from sklearn.preprocessing   import StandardScaler
from sklearn.neighbors       import KNeighborsClassifier
from sklearn.metrics         import classification_report, accuracy_score

# Load data
X, y = load_iris(return_X_y=True)

# Split: 80% train, 20% test — stratified to preserve class balance
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42, stratify=y
)

# Normalise features: mean=0, std=1 (fit on train, transform both)
scaler  = StandardScaler()
X_train = scaler.fit_transform(X_train)
X_test  = scaler.transform(X_test)

# Train a K-Nearest Neighbours classifier
model = KNeighborsClassifier(n_neighbors=5)
model.fit(X_train, y_train)

# Evaluate
y_pred = model.predict(X_test)
print(f'Accuracy: {accuracy_score(y_test, y_pred):.3f}')
print(classification_report(y_test, y_pred,
      target_names=['setosa', 'versicolor', 'virginica']))",
        ],
        'tips' => [
            'Always split data before any preprocessing — fitting the scaler on test data causes data leakage.',
            'Start with the simplest model (linear regression, KNN) and only add complexity when needed.',
            'Use stratify=y in train_test_split to preserve class proportions in imbalanced datasets.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Classical machine learning algorithms — linear regression, logistic regression, decision trees, random forests, SVM, k-means clustering — form the foundation of ML. Scikit-learn provides a clean, consistent API for all of these and is the standard toolkit for structured/tabular data. Feature engineering — creating, transforming, and selecting features — often has more impact on model performance than algorithm choice.</p>',
        'concepts' => [
            'Linear regression: ordinary least squares, coefficient interpretation, R²',
            'Logistic regression: sigmoid function, log-odds, decision boundary',
            'Decision trees: entropy/gini impurity, depth, leaf nodes',
            'Random forests: bagging, feature importance, out-of-bag error',
            'SVM: hyperplane, kernel trick (linear, RBF, polynomial)',
            'K-means clustering: centroid initialisation, elbow method, silhouette score',
            'Feature engineering: encoding categoricals, log transform, polynomial features',
        ],
        'code' => [
            'title'   => 'Random forest with cross-validation',
            'lang'    => 'python',
            'content' =>
"import numpy as np
from sklearn.ensemble        import RandomForestClassifier
from sklearn.model_selection import cross_val_score, GridSearchCV
from sklearn.datasets        import load_breast_cancer
from sklearn.pipeline        import Pipeline
from sklearn.preprocessing   import StandardScaler

X, y = load_breast_cancer(return_X_y=True)

# Pipeline: preprocessing + model in one object
pipe = Pipeline([
    ('scaler', StandardScaler()),
    ('rf',     RandomForestClassifier(random_state=42)),
])

# 5-fold CV — estimate generalisation performance
cv_scores = cross_val_score(pipe, X, y, cv=5, scoring='roc_auc')
print(f'CV ROC-AUC: {cv_scores.mean():.3f} ± {cv_scores.std():.3f}')

# Hyperparameter tuning
param_grid = {
    'rf__n_estimators':  [100, 200],
    'rf__max_depth':     [None, 10, 20],
    'rf__min_samples_split': [2, 5],
}
gs = GridSearchCV(pipe, param_grid, cv=5, scoring='roc_auc', n_jobs=-1)
gs.fit(X, y)
print('Best params:', gs.best_params_)
print('Best ROC-AUC:', gs.best_score_:.3f)",
        ],
        'tips' => [
            'Use Pipeline to bundle preprocessing and modelling — it prevents data leakage in cross-validation.',
            'ROC-AUC is a better metric than accuracy for imbalanced classification problems.',
            'Feature importance from random forest is a fast way to identify which features matter most.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Deep learning with neural networks — layers of learnable linear transformations followed by non-linear activations — can learn complex patterns directly from raw data (images, text, audio) without manual feature engineering. PyTorch and TensorFlow/Keras are the leading frameworks. Understanding forward pass, loss functions, backpropagation, and gradient descent is the core of deep learning.</p>',
        'concepts' => [
            'Neural network building blocks: layers, activations (ReLU, sigmoid, softmax), weights, biases',
            'Loss functions: MSE (regression), cross-entropy (classification), focal loss',
            'Optimisers: SGD, Adam, AdamW, learning rate schedulers',
            'Backpropagation: the chain rule applied to compute gradients',
            'Regularisation: dropout, L2 weight decay, batch normalisation',
            'PyTorch: tensor operations, autograd, nn.Module, DataLoader, training loop',
            'Convolutional Neural Networks (CNNs): conv layers, pooling, feature maps',
        ],
        'code' => [
            'title'   => 'PyTorch training loop',
            'lang'    => 'python',
            'content' =>
"import torch
import torch.nn as nn
from torch.utils.data import DataLoader

class MLP(nn.Module):
    def __init__(self, in_features: int, num_classes: int):
        super().__init__()
        self.net = nn.Sequential(
            nn.Linear(in_features, 128),
            nn.BatchNorm1d(128),
            nn.ReLU(),
            nn.Dropout(0.3),
            nn.Linear(128, 64),
            nn.ReLU(),
            nn.Linear(64, num_classes),
        )
    def forward(self, x: torch.Tensor) -> torch.Tensor:
        return self.net(x)

def train_epoch(model, loader, optimiser, criterion, device):
    model.train()
    total_loss = correct = 0
    for X, y in loader:
        X, y = X.to(device), y.to(device)
        optimiser.zero_grad()
        logits = model(X)
        loss   = criterion(logits, y)
        loss.backward()
        optimiser.step()
        total_loss += loss.item() * len(y)
        correct    += (logits.argmax(1) == y).sum().item()
    return total_loss / len(loader.dataset), correct / len(loader.dataset)",
        ],
        'tips' => [
            'Call optimiser.zero_grad() before every backward pass — gradients accumulate by default in PyTorch.',
            'Use model.train() during training and model.eval() during inference — they control dropout and BatchNorm.',
            'Move data to the same device as the model (.to(device)) before every forward pass.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Transformer models — the architecture behind BERT, GPT, T5, and LLaMA — replaced RNNs as the dominant architecture for NLP and are now used in vision (ViT) and multimodal (CLIP, Gemini) tasks. The self-attention mechanism enables transformers to capture long-range dependencies across sequences in parallel. Hugging Face Transformers provides pre-trained models for fine-tuning on downstream tasks.</p><p>Transfer learning — adapting a large pre-trained model to a new task with a small dataset — is the dominant paradigm in modern deep learning, dramatically reducing data and compute requirements.</p>',
        'concepts' => [
            'Transformer architecture: self-attention, multi-head attention, positional encoding, encoder-decoder',
            'Pre-training and fine-tuning: large pre-trained model → task-specific head',
            'Hugging Face: transformers library, AutoModel, AutoTokenizer, Trainer API',
            'BERT: bidirectional encoder, MLM and NSP pre-training',
            'GPT: autoregressive decoder, next-token prediction',
            'Parameter-efficient fine-tuning: LoRA, QLoRA, prefix tuning, adapters',
            'Evaluation: BLEU, ROUGE, BERTScore for NLP; FID, IS for image generation',
        ],
        'code' => [
            'title'   => 'Fine-tune a BERT classifier with Hugging Face',
            'lang'    => 'python',
            'content' =>
"from transformers import (
    AutoTokenizer, AutoModelForSequenceClassification,
    TrainingArguments, Trainer
)
from datasets import load_dataset
import numpy as np
import evaluate

tokenizer = AutoTokenizer.from_pretrained('distilbert-base-uncased')
model     = AutoModelForSequenceClassification.from_pretrained(
    'distilbert-base-uncased', num_labels=2
)

ds = load_dataset('imdb')

def tokenize(batch):
    return tokenizer(batch['text'], truncation=True, padding='max_length', max_length=256)

ds_tokenised = ds.map(tokenize, batched=True)
accuracy = evaluate.load('accuracy')

def compute_metrics(eval_pred):
    logits, labels = eval_pred
    preds = np.argmax(logits, axis=-1)
    return accuracy.compute(predictions=preds, references=labels)

trainer = Trainer(
    model=model,
    args=TrainingArguments(
        output_dir='./results',
        num_train_epochs=3,
        per_device_train_batch_size=16,
        evaluation_strategy='epoch',
        save_strategy='epoch',
        load_best_model_at_end=True,
    ),
    train_dataset=ds_tokenised['train'],
    eval_dataset=ds_tokenised['test'],
    compute_metrics=compute_metrics,
)
trainer.train()",
        ],
        'tips' => [
            'Start with DistilBERT or DistilGPT2 — they are 40% faster and 60% smaller than full BERT/GPT.',
            'Use LoRA for fine-tuning large models — it updates < 1% of parameters and dramatically reduces memory.',
            'Freeze backbone layers and train only the classification head first — it is faster and avoids catastrophic forgetting.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert AI engineering encompasses the full lifecycle of production AI systems — data collection and labelling pipelines, model training at scale with distributed training (DDP, FSDP), ML experiment tracking (MLflow, Weights & Biases), model serving (TorchServe, Triton), A/B testing, and continuous monitoring for data drift and model degradation.</p><p>Responsible AI — bias detection and mitigation, model interpretability (SHAP, LIME, attention visualisation), fairness metrics, and AI governance frameworks — is an increasingly critical dimension of expert AI practice, as AI systems are deployed in high-stakes domains.</p>',
        'concepts' => [
            'Distributed training: DDP (DistributedDataParallel), FSDP (Fully Sharded Data Parallel), DeepSpeed',
            'Mixed precision training: FP16, BF16, automatic mixed precision (AMP)',
            'MLflow / Weights & Biases: experiment tracking, model registry, hyperparameter sweeps',
            'Model serving: ONNX export, TorchScript, Triton Inference Server, TFLite',
            'Data and model drift monitoring: Evidently, Arize, Fiddler',
            'Interpretability: SHAP values, LIME, integrated gradients, attention maps',
            'AI governance: model cards, data sheets, fairness metrics, bias audits',
        ],
        'code' => [
            'title'   => 'MLflow experiment tracking',
            'lang'    => 'python',
            'content' =>
"import mlflow
import mlflow.pytorch

mlflow.set_experiment('sentiment-classifier')

with mlflow.start_run(run_name='distilbert-lora-v2'):
    # Log hyperparameters
    mlflow.log_params({
        'model':           'distilbert-base-uncased',
        'peft_method':     'lora',
        'lora_rank':       8,
        'epochs':          3,
        'batch_size':      16,
        'learning_rate':   2e-4,
    })

    # Training loop (abbreviated)
    for epoch in range(3):
        train_loss, train_acc = train_epoch(...)
        eval_loss,  eval_acc  = evaluate(...)
        mlflow.log_metrics({
            'train/loss': train_loss,
            'train/acc':  train_acc,
            'eval/loss':  eval_loss,
            'eval/acc':   eval_acc,
        }, step=epoch)

    # Log the final model
    mlflow.pytorch.log_model(model, 'model', registered_model_name='SentimentClassifier')
    mlflow.log_artifact('confusion_matrix.png')

    # Transition to production in the model registry
    client = mlflow.MlflowClient()
    client.set_registered_model_alias('SentimentClassifier', 'production', version=3)",
        ],
        'tips' => [
            'Log everything — hyperparameters, metrics, artefacts — from the start; you will need that experiment history.',
            'Use model cards (modelcards.info) to document your model\'s intended use, limitations, and fairness analysis.',
            'Monitor production models for data drift with statistical tests (KS test, PSI) — models degrade silently.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
