<?php
$tutorial_title = 'Machine Learning';
$tutorial_slug  = 'machine-learning';
$quiz_slug      = 'machine-learning';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Machine Learning is the subfield of AI that gives computers the ability to learn from data without being explicitly programmed. Instead of writing rules, you provide examples and an algorithm discovers patterns. ML has transformed fields from fraud detection and medical diagnosis to recommendation systems and autonomous vehicles. Python\'s scikit-learn, pandas, and NumPy form the standard beginner toolkit.</p>',
        'concepts' => [
            'Supervised learning: classification (discrete labels) and regression (continuous values)',
            'Unsupervised learning: clustering, dimensionality reduction, anomaly detection',
            'Reinforcement learning: agent, environment, reward, policy',
            'Training set, validation set, test set: why three splits?',
            'Loss functions: MSE (regression), binary cross-entropy, categorical cross-entropy',
            'Evaluation metrics: accuracy, precision, recall, F1, MAE, RMSE, R²',
            'The ML workflow: problem definition → data → features → model → evaluate → deploy',
        ],
        'code' => [
            'title'   => 'Complete ML pipeline with scikit-learn',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import numpy as np
from sklearn.model_selection  import train_test_split
from sklearn.pipeline         import Pipeline
from sklearn.compose          import ColumnTransformer
from sklearn.preprocessing    import StandardScaler, OneHotEncoder
from sklearn.ensemble         import GradientBoostingClassifier
from sklearn.metrics          import classification_report

df = pd.read_csv('titanic.csv')

# Feature engineering
df['FamilySize']  = df['SibSp'] + df['Parch'] + 1
df['IsAlone']     = (df['FamilySize'] == 1).astype(int)
df['Title']       = df['Name'].str.extract(' ([A-Za-z]+)\\.', expand=False)
df['Title']       = df['Title'].replace(['Lady','Countess','Sir','Don','Jonkheer'], 'Rare')

X = df[['Pclass', 'Sex', 'Age', 'Fare', 'Embarked', 'FamilySize', 'Title']]
y = df['Survived']

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42, stratify=y)

preprocess = ColumnTransformer([
    ('num', StandardScaler(),  ['Age', 'Fare', 'FamilySize']),
    ('cat', OneHotEncoder(handle_unknown='ignore'), ['Sex', 'Embarked', 'Title']),
])

pipe = Pipeline([('prep', preprocess), ('clf', GradientBoostingClassifier(random_state=42))])
pipe.fit(X_train, y_train)
print(classification_report(y_test, pipe.predict(X_test)))",
        ],
        'tips' => [
            'Always use a Pipeline for ML workflows — it prevents data leakage and makes deployment trivial.',
            'Feature engineering often matters more than algorithm choice — spend most time here.',
            'Use stratify=y in train_test_split for classification problems with class imbalance.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Understanding your data is as important as building models. Exploratory Data Analysis (EDA) with pandas and matplotlib/seaborn reveals distributions, correlations, missing values, and outliers. Data preprocessing — handling missing values, encoding categoricals, scaling numerics — is the foundation that determines model quality. Poor preprocessing leads to poor models, regardless of algorithm sophistication.</p>',
        'concepts' => [
            'EDA: df.describe(), df.info(), value_counts(), correlation matrix, pair plots',
            'Missing values: imputation strategies (mean, median, mode, KNN, iterative)',
            'Categorical encoding: label encoding, one-hot encoding, target encoding, ordinal',
            'Feature scaling: StandardScaler, MinMaxScaler, RobustScaler (for outliers)',
            'Outlier detection: IQR method, z-score, Isolation Forest, Local Outlier Factor',
            'Class imbalance: oversampling (SMOTE), undersampling, class_weight parameter',
            'Feature selection: correlation, mutual information, Lasso regularisation, RFE',
        ],
        'code' => [
            'title'   => 'EDA and preprocessing pipeline',
            'lang'    => 'python',
            'content' =>
"import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from sklearn.impute      import SimpleImputer
from sklearn.pipeline    import Pipeline
from sklearn.compose     import ColumnTransformer
from sklearn.preprocessing import StandardScaler, OneHotEncoder

df = pd.read_csv('data.csv')

# Quick EDA
print(df.shape)
print(df.dtypes)
print(df.isnull().sum().sort_values(ascending=False))
print(df.describe())

# Correlation heatmap
plt.figure(figsize=(10, 8))
sns.heatmap(df.select_dtypes(include='number').corr(),
            annot=True, fmt='.2f', cmap='coolwarm')
plt.tight_layout()
plt.savefig('correlation.png')

# Distribution of target variable
df['target'].value_counts(normalize=True).plot(kind='bar')

# Robust preprocessing pipeline
num_features  = df.select_dtypes(include='number').columns.tolist()
cat_features  = df.select_dtypes(include='object').columns.tolist()

preprocessor = ColumnTransformer([
    ('num', Pipeline([('imp', SimpleImputer(strategy='median')),
                      ('scl', StandardScaler())]), num_features),
    ('cat', Pipeline([('imp', SimpleImputer(strategy='most_frequent')),
                      ('enc', OneHotEncoder(handle_unknown='ignore'))]), cat_features),
])",
        ],
        'tips' => [
            'Visualise class distribution before modelling — imbalanced classes require special handling.',
            'Use RobustScaler instead of StandardScaler when your data has outliers — it uses median and IQR.',
            'Plot feature distributions after scaling to verify there are no remaining anomalies.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Ensemble methods — bagging (Random Forest), boosting (XGBoost, LightGBM, CatBoost), and stacking — consistently top ML competitions and industry benchmarks for tabular data. XGBoost and LightGBM are the workhorses of Kaggle competitions and real-world tabular ML; they combine gradient boosting with regularisation, sparse data handling, and GPU acceleration.</p><p>Cross-validation, hyperparameter tuning (GridSearchCV, Optuna), learning curves, and confusion matrices form the evaluation toolkit that separates disciplined ML engineering from trial-and-error model fitting.</p>',
        'concepts' => [
            'Gradient boosting: weak learners, additive training, learning rate, tree depth',
            'XGBoost: regularised gradient boosting, column subsampling, early stopping',
            'LightGBM: leaf-wise tree growth, histogram-based binning, much faster than XGBoost',
            'CatBoost: native categorical feature handling, ordered boosting',
            'Hyperparameter tuning: Optuna (Bayesian optimisation), cross-val score objective',
            'SHAP values: explaining individual predictions and global feature importance',
            'Learning curves: diagnosing overfitting/underfitting from train/val error curves',
        ],
        'code' => [
            'title'   => 'XGBoost with Optuna tuning',
            'lang'    => 'python',
            'content' =>
"import optuna
import xgboost as xgb
from sklearn.model_selection import cross_val_score
from sklearn.datasets        import make_classification

X, y = make_classification(n_samples=10000, n_features=20, random_state=42)

def objective(trial):
    params = {
        'n_estimators':      trial.suggest_int('n_estimators',   100, 500),
        'max_depth':         trial.suggest_int('max_depth',       3,   9),
        'learning_rate':     trial.suggest_float('learning_rate', 0.01, 0.3, log=True),
        'subsample':         trial.suggest_float('subsample',     0.6,  1.0),
        'colsample_bytree':  trial.suggest_float('colsample_bytree', 0.6, 1.0),
        'reg_alpha':         trial.suggest_float('reg_alpha',     1e-8, 10.0, log=True),
        'reg_lambda':        trial.suggest_float('reg_lambda',    1e-8, 10.0, log=True),
        'use_label_encoder': False,
        'eval_metric':       'logloss',
    }
    model = xgb.XGBClassifier(**params, random_state=42)
    return cross_val_score(model, X, y, cv=5, scoring='roc_auc').mean()

study = optuna.create_study(direction='maximize')
study.optimize(objective, n_trials=50, n_jobs=-1)

print('Best ROC-AUC:', study.best_value)
print('Best params:', study.best_params)",
        ],
        'tips' => [
            'LightGBM is 3–10× faster than XGBoost on most datasets — try it first for large datasets.',
            'Use Optuna over GridSearchCV — Bayesian optimisation finds good parameters in far fewer trials.',
            'Always use early_stopping_rounds with XGBoost/LightGBM on a validation set — it prevents overfitting.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Production ML engineering — feature stores, model registries, online inference, batch scoring, data and model drift monitoring, and continuous training pipelines — transforms one-off models into reliable data products. MLflow, Weights & Biases, and Vertex AI provide the experiment tracking, model registry, and deployment infrastructure that production ML requires.</p>',
        'concepts' => [
            'Feature stores: Feast, Hopsworks; online vs. offline features; point-in-time correctness',
            'ML pipelines: Kubeflow Pipelines, Vertex AI Pipelines, Apache Airflow for ML',
            'Model serving: REST API (FastAPI + joblib), batch scoring, A/B testing',
            'Data drift detection: population stability index (PSI), Kolmogorov-Smirnov test',
            'Model drift: concept drift vs. covariate shift; evidently.ai for monitoring',
            'Continuous training: triggers for retraining (schedule, drift alert, performance drop)',
            'Fairness and bias: disparate impact, equalised odds, demographic parity',
        ],
        'code' => [
            'title'   => 'MLflow model lifecycle',
            'lang'    => 'python',
            'content' =>
"import mlflow
import mlflow.sklearn
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics  import roc_auc_score

mlflow.set_experiment('churn-prediction')
mlflow.sklearn.autolog()  # auto-logs params, metrics, model

with mlflow.start_run(run_name='rf-v3'):
    model = RandomForestClassifier(n_estimators=200, max_depth=10, random_state=42)
    model.fit(X_train, y_train)

    roc_auc = roc_auc_score(y_test, model.predict_proba(X_test)[:, 1])
    mlflow.log_metric('test_roc_auc', roc_auc)

    # Register model
    mlflow.sklearn.log_model(
        model, 'model',
        registered_model_name='ChurnClassifier',
        signature=mlflow.models.infer_signature(X_test, model.predict(X_test)),
    )

# Load model for serving
model_uri = 'models:/ChurnClassifier/production'
loaded    = mlflow.sklearn.load_model(model_uri)
preds     = loaded.predict_proba(new_data)[:, 1]",
        ],
        'tips' => [
            'Use mlflow.sklearn.autolog() — it logs parameters, metrics, and the model artefact with one line.',
            'Log model signatures (input/output schema) to catch schema mismatches at inference time.',
            'Monitor PSI (Population Stability Index) > 0.2 as a trigger for retraining.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert machine learning spans AutoML systems that automatically search model architectures and hyperparameters (AutoSklearn, FLAML, H2O AutoML), meta-learning ("learning to learn"), and neural architecture search (NAS). Understanding the ML research publication pipeline — arxiv, NeurIPS, ICML, ICLR — and the ability to implement and benchmark novel methods distinguishes research-oriented ML engineers from practitioners.</p>',
        'concepts' => [
            'AutoML: model selection, feature engineering automation (AutoSklearn, FLAML, H2O)',
            'Neural Architecture Search (NAS): DARTS, EfficientNet scaling',
            'Meta-learning: few-shot learning, MAML, prototypical networks',
            'Causal ML: do-calculus, causal inference, treatment effect estimation',
            'Federated learning: training across decentralised data without centralisation',
            'ML research: arxiv cs.LG, NeurIPS/ICML/ICLR paper review process',
            'Kaggle at grandmaster level: stacking, pseudo-labelling, feature interaction mining',
        ],
        'code' => [
            'title'   => 'FLAML AutoML',
            'lang'    => 'python',
            'content' =>
"from flaml import AutoML
from sklearn.datasets import fetch_openml
from sklearn.model_selection import train_test_split

# Boston housing dataset
data    = fetch_openml(data_id=531, as_frame=True)
X_train, X_test, y_train, y_test = train_test_split(
    data.data, data.target, test_size=0.2, random_state=42
)

automl = AutoML()
automl.fit(
    X_train, y_train,
    task='regression',
    metric='rmse',
    time_budget=120,      # seconds to search
    estimator_list=['lgbm', 'xgboost', 'rf', 'extra_tree', 'catboost'],
    verbose=1,
)

print('Best estimator:', automl.best_estimator)
print('Best config:',    automl.best_config)
print('Val RMSE:',       automl.best_loss)

from sklearn.metrics import mean_squared_error, r2_score
import numpy as np
preds = automl.predict(X_test)
print('Test RMSE:', np.sqrt(mean_squared_error(y_test, preds)))
print('Test R²:',   r2_score(y_test, preds))",
        ],
        'tips' => [
            'FLAML outperforms AutoSklearn on speed — use it as a strong baseline before custom feature engineering.',
            'Implement and benchmark new arxiv papers yourself — it is the fastest way to develop ML research intuition.',
            'Follow Chip Huyen\'s blog (huyenchip.com) and Eugene Yan\'s blog for production ML engineering insights.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
