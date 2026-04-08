<?php
$tutorial_title = 'Django';
$tutorial_slug  = 'django';
$quiz_slug      = 'django';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Django is a high-level Python web framework that encourages rapid development and clean, pragmatic design. Its philosophy — "batteries included" — means Django ships with an ORM, migrations, an admin interface, authentication, forms, and security middleware out of the box. Created in 2003 for a journalism newsroom, Django now powers Instagram, Pinterest, Mozilla, and thousands of other sites.</p><p>This tier walks through creating a Django project and app, understanding the MTV (Model-Template-View) architecture, and rendering a first page.</p>',
        'concepts' => [
            'Django project vs. app: project contains settings, apps contain features',
            'django-admin startproject and python manage.py startapp',
            'settings.py: INSTALLED_APPS, DATABASES, TEMPLATES, STATIC_URL',
            'URL routing: urls.py, path(), include(), named URL patterns',
            'Views: function-based views (FBV), HttpRequest, HttpResponse',
            'Django templates: {{ variable }}, {% tag %}, template inheritance, {% extends %} / {% block %}',
            'manage.py commands: runserver, migrate, shell, createsuperuser',
        ],
        'code' => [
            'title'   => 'Django URL + view + template',
            'lang'    => 'python',
            'content' =>
"# urls.py (app-level)
from django.urls import path
from . import views

app_name = 'blog'
urlpatterns = [
    path('',         views.post_list,   name='post-list'),
    path('<int:pk>/', views.post_detail, name='post-detail'),
]

# views.py
from django.shortcuts import render, get_object_or_404
from .models import Post

def post_list(request):
    posts = Post.objects.filter(published=True).order_by('-created_at')
    return render(request, 'blog/post_list.html', {'posts': posts})

def post_detail(request, pk):
    post = get_object_or_404(Post, pk=pk, published=True)
    return render(request, 'blog/post_detail.html', {'post': post})",
        ],
        'tips' => [
            'Use get_object_or_404() instead of a try/except — it returns a proper 404 response automatically.',
            'Organise related functionality into apps (blog, accounts, shop) to keep the codebase modular.',
            'Run python manage.py check before every deployment to catch configuration errors early.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Django\'s ORM (Object-Relational Mapper) lets you define database tables as Python classes and query them with a readable, chainable API without writing SQL. Migrations track changes to your models and apply them to the database schema automatically.</p><p>Django Forms provide server-side validation, CSRF protection, and HTML rendering for both custom forms and model-backed ModelForms. The built-in admin interface — powered by your models — provides a production-ready CMS in minutes.</p>',
        'concepts' => [
            'Models: Field types, primary keys, __str__, Meta class, verbose_name',
            'Migrations: makemigrations, migrate, squashmigrations',
            'QuerySet API: filter(), exclude(), get(), all(), order_by(), annotate(), select_related()',
            'F expressions and Q objects for complex queries',
            'ModelForm: form_class, save(), instance parameter for editing',
            'Form validation: clean_field(), clean(), ValidationError',
            'Django admin: register(), ModelAdmin, list_display, search_fields, list_filter',
        ],
        'code' => [
            'title'   => 'Django model and ModelForm',
            'lang'    => 'python',
            'content' =>
"from django.db import models
from django.forms import ModelForm

class Post(models.Model):
    title     = models.CharField(max_length=200)
    slug      = models.SlugField(unique=True)
    body      = models.TextField()
    published = models.BooleanField(default=False)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)
    author    = models.ForeignKey('auth.User', on_delete=models.CASCADE, related_name='posts')

    class Meta:
        ordering = ['-created_at']

    def __str__(self):
        return self.title

class PostForm(ModelForm):
    class Meta:
        model  = Post
        fields = ['title', 'slug', 'body', 'published']

    def clean_title(self):
        title = self.cleaned_data['title']
        if len(title) < 5:
            raise forms.ValidationError('Title must be at least 5 characters.')
        return title.strip()",
        ],
        'tips' => [
            'Use select_related() for ForeignKey and prefetch_related() for ManyToMany to avoid N+1 queries.',
            'Always add unique=True to slug fields and generate slugs with django.utils.text.slugify().',
            'Customise ModelAdmin with list_display and search_fields — it makes the admin genuinely usable.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Class-based views (CBVs) provide reusable, composable view logic through Python inheritance. Django ships with generic views (ListView, DetailView, CreateView, UpdateView, DeleteView) that implement the most common patterns with minimal code. Mixins add orthogonal behaviour — login requirement, permission checks — in a composable way.</p><p>Django REST Framework (DRF) turns Django into a powerful API server with serializers, viewsets, routers, authentication classes, and browsable API documentation.</p>',
        'concepts' => [
            'Class-based views: View, TemplateView, ListView, DetailView, CreateView, UpdateView, DeleteView',
            'LoginRequiredMixin, PermissionRequiredMixin, UserPassesTestMixin',
            'Django middleware: process_request, process_response, process_exception',
            'DRF: Serializer, ModelSerializer, APIView, ViewSet, Router',
            'DRF authentication: SessionAuthentication, TokenAuthentication, JWT (simplejwt)',
            'DRF permissions: IsAuthenticated, IsAdminUser, custom permission classes',
            'Pagination: PageNumberPagination, CursorPagination',
        ],
        'code' => [
            'title'   => 'DRF ModelSerializer and ViewSet',
            'lang'    => 'python',
            'content' =>
"from rest_framework import serializers, viewsets, permissions
from .models import Post

class PostSerializer(serializers.ModelSerializer):
    author_name = serializers.CharField(source='author.get_full_name', read_only=True)

    class Meta:
        model  = Post
        fields = ['id', 'title', 'slug', 'body', 'published', 'author_name', 'created_at']
        read_only_fields = ['id', 'created_at']

class PostViewSet(viewsets.ModelViewSet):
    serializer_class   = PostSerializer
    permission_classes = [permissions.IsAuthenticatedOrReadOnly]

    def get_queryset(self):
        qs = Post.objects.select_related('author').order_by('-created_at')
        if not self.request.user.is_authenticated:
            qs = qs.filter(published=True)
        return qs

    def perform_create(self, serializer):
        serializer.save(author=self.request.user)",
        ],
        'tips' => [
            'Override get_queryset() instead of queryset = ... to apply per-request filtering and authentication.',
            'Use perform_create() and perform_update() to inject server-side data (like the current user) during save.',
            'Add DEFAULT_AUTHENTICATION_CLASSES and DEFAULT_PERMISSION_CLASSES to DRF settings as secure defaults.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Django\'s asynchronous support (async views, async ORM, async middleware) enables high-concurrency applications with Django Channels for WebSocket and long-polling connections. Celery with Redis or RabbitMQ handles background tasks — email sending, data processing, scheduled jobs — offloaded from the request cycle.</p><p>Caching strategies — per-view cache, template fragment cache, low-level cache API with Redis — dramatically reduce database load. Django\'s signals framework decouples side effects from core business logic.</p>',
        'concepts' => [
            'Async views: async def view(request) and await in Django 3.1+',
            'Django Channels: ASGI, WebSocket consumers, channel layers',
            'Celery: task definition, delay(), apply_async(), periodic tasks (beat)',
            'Caching: cache_page, cache.set/get, cache.delete, fragment cache',
            'Django signals: post_save, pre_delete, m2m_changed, Signal.connect()',
            'Custom management commands for scripts and maintenance tasks',
            'Django Debug Toolbar for query analysis and performance diagnosis',
        ],
        'code' => [
            'title'   => 'Celery task with retry',
            'lang'    => 'python',
            'content' =>
"from celery import shared_task
from celery.utils.log import get_task_logger

logger = get_task_logger(__name__)

@shared_task(bind=True, max_retries=3, default_retry_delay=60)
def send_welcome_email(self, user_id: int):
    from django.contrib.auth import get_user_model
    from django.core.mail import send_mail

    User = get_user_model()
    try:
        user = User.objects.get(pk=user_id)
        send_mail(
            subject='Welcome to CodeFoundry!',
            message=f'Hi {user.first_name}, your account is ready.',
            from_email='noreply@example.com',
            recipient_list=[user.email],
        )
        logger.info('Welcome email sent to %s', user.email)
    except User.DoesNotExist:
        logger.error('User %d not found — not retrying', user_id)
    except Exception as exc:
        raise self.retry(exc=exc)",
        ],
        'tips' => [
            'Use bind=True in Celery tasks to access self.retry() for automatic retry with exponential backoff.',
            'Profile with Django Debug Toolbar in development — every page with 50+ queries needs N+1 investigation.',
            'Decouple side effects (email, push notification) from model saves with post_save signals or Celery tasks.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Django development encompasses custom database backends and query optimisations — window functions, CTEs, partial indexes, and database-specific features exposed through Django\'s ORM. Multi-tenancy strategies (schema-per-tenant vs. row-level with tenant_id), large-scale deployment patterns, and the security hardening checklist complete the expert curriculum.</p><p>Contributing to Django core, writing reusable Django packages with PyPI distribution, and designing Django applications that scale to billions of rows and millions of concurrent users represent the pinnacle of Django expertise.</p>',
        'concepts' => [
            'Window functions: Window, Rank, Lead, Lag, FirstValue in ORM',
            'Raw SQL and RawQuerySet: when and how to escape safely',
            'Database indexes: Index, UniqueConstraint, GinIndex for JSON/array fields',
            'Multi-tenancy: django-tenants (schema-per-tenant) vs. row-level isolation',
            'Django security checklist: SECURE_* settings, HSTS, CSP, cookie flags',
            'Connection pooling: PgBouncer, django-db-geventpool',
            'Django packaging: app config, default_app_config, PyPI distribution',
            'Asynchronous ORM: sync_to_async, database_sync_to_async wrappers',
        ],
        'code' => [
            'title'   => 'ORM window function — ranking users',
            'lang'    => 'python',
            'content' =>
"from django.db.models import Window, F, IntegerField
from django.db.models.functions import Rank, DenseRank

# Rank users by score within each department using a window function
ranked_users = User.objects.annotate(
    rank=Window(
        expression=Rank(),
        partition_by=[F('department')],
        order_by=F('score').desc(),
    ),
    dense_rank=Window(
        expression=DenseRank(),
        partition_by=[F('department')],
        order_by=F('score').desc(),
    ),
).order_by('department', 'rank')

for u in ranked_users:
    print(f'{u.department}: #{u.rank} {u.name} (score: {u.score})')",
        ],
        'tips' => [
            'Use EXPLAIN ANALYZE on slow queries from Debug Toolbar — partial indexes often cut query time by 10×.',
            'Apply Django\'s deployment security checklist (manage.py check --deploy) before every production release.',
            'Follow the Django fellows blog and DjangoCon talks for advanced architectural patterns.',
            'Contribute to Django\'s ticket tracker (code.djangoproject.com) — even documentation patches help.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
