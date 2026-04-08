<?php
$tutorial_title = 'Ruby';
$tutorial_slug  = 'ruby';
$quiz_slug      = 'ruby';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Ruby is a dynamic, interpreted, object-oriented scripting language created by Yukihiro "Matz" Matsumoto and first released in 1995. Its design philosophy — "optimised for developer happiness" — produces code that reads almost like English. Everything in Ruby is an object, including integers and nil. Ruby powers GitHub, Shopify, Airbnb, and countless startups through its web framework Rails.</p><p>This tier introduces Ruby syntax, IRB (interactive Ruby shell), and the expressive idioms that make Ruby a joy to write.</p>',
        'concepts' => [
            'IRB: interactive Ruby shell for exploration',
            'Variables: local (snake_case), instance (@var), class (@@var), global ($var), constants (CAPS)',
            'Everything is an object: 42.class, true.class, nil.class',
            'String interpolation: "Hello, #{name}!"',
            'Symbols: :name vs. "name" — immutable, memory-efficient identifiers',
            'Control flow: if/elsif/else, unless, while, until, case/when, and ternary',
            'Blocks: do...end and { } — closures passed to methods',
        ],
        'code' => [
            'title'   => 'Ruby blocks and iterators',
            'lang'    => 'ruby',
            'content' =>
'# Everything is an object
puts 42.class    # Integer
puts "hi".upcase # HI

# Blocks with Enumerable
numbers = [3, 1, 4, 1, 5, 9, 2, 6]

doubled  = numbers.map    { |n| n * 2 }
evens    = numbers.select { |n| n.even? }
total    = numbers.reduce(0) { |sum, n| sum + n }

# Equivalent with symbol-to-proc shorthand
words = %w[hello world ruby]
uppers = words.map(&:upcase)   # ["HELLO", "WORLD", "RUBY"]

# Range iteration
(1..5).each { |i| print "#{i} " }  # 1 2 3 4 5
puts

# Hash
scores = { alice: 92, bob: 74, carol: 88 }
scores.sort_by { |_, v| -v }.first(2).each do |name, score|
  puts "#{name}: #{score}"
end',
        ],
        'tips' => [
            'Use the symbol-to-proc shorthand (&:method_name) for single-method blocks — it is idiomatic Ruby.',
            'Prefer %w[] for arrays of strings and %i[] for arrays of symbols to reduce punctuation.',
            'Use unless instead of if !condition when it reads more naturally in English.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Ruby classes use a pure single-inheritance model extended by mixins: modules included into a class provide reusable sets of methods. The two most important built-in mixins are <code>Enumerable</code> (provides map/select/reduce when you define <code>each</code>) and <code>Comparable</code> (provides <, <=, >, >= when you define <=>).</p><p>Exception handling (begin/rescue/ensure/raise), file I/O, regular expressions, and the powerful string methods complete the beginner foundation.</p>',
        'concepts' => [
            'Classes: initialize, attr_accessor/reader/writer, instance methods',
            'Inheritance: class Dog < Animal; super',
            'Modules and mixins: module M; include/extend/prepend',
            'Enumerable mixin: include it and define each to get map/select/reject/reduce/sort',
            'Comparable mixin: include it and define <=> to get comparison operators',
            'Exception handling: begin/rescue/ensure/raise, custom exception classes',
            'Regular expressions: /pattern/, match?, scan, gsub, =~',
        ],
        'code' => [
            'title'   => 'Ruby mixin with Enumerable',
            'lang'    => 'ruby',
            'content' =>
'module Taggable
  def tags
    @tags ||= []
  end

  def add_tag(tag)
    tags << tag.to_s.downcase.strip
    self
  end

  def tagged_with?(tag)
    tags.include?(tag.to_s.downcase)
  end
end

class Post
  include Comparable
  include Taggable

  attr_accessor :title, :created_at

  def initialize(title)
    @title      = title
    @created_at = Time.now
  end

  # Comparable requires <=> — gives us <, <=, >, >=, between?, clamp
  def <=>(other)
    created_at <=> other.created_at
  end

  def to_s = "Post(#{title})"
end

p1 = Post.new("Hello").add_tag("ruby").add_tag("intro")
puts p1.tagged_with?(:ruby)  # true
puts p1.tags.inspect          # ["ruby", "intro"]',
        ],
        'tips' => [
            'Use attr_accessor only for attributes that callers should read AND write — use attr_reader for read-only.',
            'Freeze string literals with # frozen_string_literal: true at the top of every file for performance.',
            'Module#prepend wraps the original method, enabling method decoration without alias_method hacks.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Ruby on Rails is the dominant Ruby web framework — an opinionated, convention-over-configuration MVC framework that made rapid web development mainstream. Its ActiveRecord ORM, convention-based routing, Action Mailer, Active Job, and built-in test suite make building CRUD applications extraordinarily fast.</p><p>Metaprogramming — Ruby\'s ability to define methods at runtime, open classes, use method_missing, and define_method — is what makes Rails\' expressive DSLs (has_many, validates, before_action) possible. Understanding metaprogramming unlocks advanced Ruby patterns.</p>',
        'concepts' => [
            'Rails MVC: models, views, controllers; rails new and scaffold generation',
            'ActiveRecord: migrations, associations (has_many, belongs_to, has_and_belongs_to_many)',
            'Rails routing: resources, nested resources, path helpers',
            'Metaprogramming: define_method, method_missing, respond_to_missing?, send, class_eval',
            'Open classes: adding methods to existing classes (monkey patching)',
            'Proc, lambda, and the -> stabby lambda syntax',
            'Yielding and blocks: yield, block_given?, &block capture',
        ],
        'code' => [
            'title'   => 'Ruby metaprogramming — dynamic attribute readers',
            'lang'    => 'ruby',
            'content' =>
'module StrongTyped
  def self.included(base)
    base.extend(ClassMethods)
  end

  module ClassMethods
    def typed_attr(name, type)
      define_method(name) do
        instance_variable_get(:"@#{name}")
      end

      define_method(:"#{name}=") do |val|
        raise TypeError, "#{name} must be a #{type}" unless val.is_a?(type)
        instance_variable_set(:"@#{name}", val)
      end
    end
  end
end

class Config
  include StrongTyped

  typed_attr :port,    Integer
  typed_attr :host,    String
  typed_attr :debug,   TrueClass

  def initialize(host:, port:, debug: false)
    self.host  = host
    self.port  = port
    self.debug = debug
  end
end

cfg = Config.new(host: "localhost", port: 3000)
cfg.port = "wrong"  # raises TypeError: port must be a Integer',
        ],
        'tips' => [
            'Always define respond_to_missing? when you implement method_missing — tools and duck-typing rely on it.',
            'Use define_method instead of eval for dynamic method definitions — it is safer and faster.',
            'Understand Rails\' callback chains (before_action, after_save) — they are method_missing and module_eval under the hood.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced Ruby covers performance optimisation — object allocation profiling with allocation_tracer, memory profiling with memory_profiler, and understanding how Ruby\'s garbage collector (GC) works. Background jobs with Sidekiq (Redis-backed, multi-threaded), caching with Rails cache stores, and Action Cable for WebSockets complete the production Ruby stack.</p><p>Testing in Ruby is a cultural practice: RSpec\'s DSL produces readable specifications, FactoryBot generates test data, and VCR/WebMock controls HTTP interactions in tests. Writing good tests is as important as writing good code in the Ruby ecosystem.</p>',
        'concepts' => [
            'RSpec: describe, context, it, before, let, expect(...).to, shared_examples',
            'FactoryBot: factory definitions, traits, sequences, build/create strategies',
            'Sidekiq: workers, queues, retries, batches, scheduled jobs',
            'Rails caching: cache store configuration, fragment caching, Russian doll caching',
            'Action Cable: Channel, stream_from, broadcast_to',
            'Ruby memory profiling: memory_profiler gem, GC.stat, ObjectSpace',
            'Ractors (Ruby 3): actor model for parallel execution bypassing the GIL',
        ],
        'code' => [
            'title'   => 'Sidekiq worker with retry',
            'lang'    => 'ruby',
            'content' =>
'class WelcomeEmailWorker
  include Sidekiq::Worker

  sidekiq_options queue: :mailers, retry: 5, backtrace: true

  sidekiq_retry_in do |count, exception|
    case exception
    when Net::OpenTimeout, Net::ReadTimeout
      [10, 30, 60, 300, 600][count] || 600  # exponential-ish backoff
    else
      30 * (count + 1)
    end
  end

  def perform(user_id)
    user = User.find(user_id)
    WelcomeMailer.welcome(user).deliver_now
    logger.info "Welcome email sent to #{user.email}"
  rescue ActiveRecord::RecordNotFound
    logger.warn "User #{user_id} not found — skipping"
    # Do not re-raise — no point in retrying a non-existent user
  end
end

# Enqueue:
# WelcomeEmailWorker.perform_async(user.id)
# WelcomeEmailWorker.perform_in(5.minutes, user.id)',
        ],
        'tips' => [
            'Use perform_async, not perform_sync, for email delivery — keep the request cycle fast.',
            'Test Sidekiq workers with the :fake adapter (Sidekiq::Testing.fake!) to avoid Redis in unit tests.',
            'Profile object allocations with memory_profiler on any Rails action that feels slow.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Ruby involves understanding the MRI Ruby interpreter (CRuby) internals — the YARV bytecode VM, the GIL (Global VM Lock) and its implications for threading, incremental garbage collection, and how JRuby and TruffleRuby remove the GIL for true thread parallelism. Writing C extensions with the Ruby C API extends Ruby with native performance where needed.</p><p>Contributing to Ruby core (submitting bugs, patches, and proposals to the ruby/ruby GitHub repository), the Ruby mailing list, and RubyConf/RailsConf represent the community dimension of expert Ruby mastery. Ruby 3.x\'s three targets — 3× performance (YJIT), static typing (RBS), and concurrency (Ractors and Fibers) — define the language\'s future direction.</p>',
        'concepts' => [
            'MRI YARV VM: bytecode instructions, RubyVM::InstructionSequence.disasm',
            'YJIT: in-process JIT compiler (Ruby 3.1+), enabling --yjit',
            'GVL (Global VM Lock): threads vs. processes, JRuby/TruffleRuby alternatives',
            'Ruby GC: incremental, generational, compacting (Ruby 2.7+ with GC.compact)',
            'RBS: type signatures in .rbs files, Steep and Sorbet for type checking',
            'Ruby C extensions: Init_#{name}, rb_define_method, VALUE type',
            'Fibers and Fiber::Scheduler for non-blocking I/O in Ruby 3',
        ],
        'code' => [
            'title'   => 'Non-blocking Fiber scheduler (Ruby 3)',
            'lang'    => 'ruby',
            'content' =>
'# Ruby 3 Fiber::Scheduler allows non-blocking I/O without callbacks
require "async"  # gem "async"

Async do
  task1 = Async { sleep 0.1; "result 1" }
  task2 = Async { sleep 0.1; "result 2" }

  # Both run concurrently — total time ~0.1s, not 0.2s
  puts task1.wait
  puts task2.wait
end

# Under the hood, Async uses Fiber::Scheduler hooks:
# io_wait, kernel_sleep, address_resolve, fiber_schedule
# The event loop suspends fibers waiting on I/O and
# resumes them when the I/O completes — no threads needed.',
        ],
        'tips' => [
            'Enable YJIT with RUBYOPT="--yjit" or in Dockerfile — it provides 10–40% speedup on most Rails apps.',
            'Add RBS type signatures incrementally, starting with the most critical service objects.',
            'Read "Ruby Hacking Guide" (online) for a deep walk through the MRI source code.',
            'Follow ruby/ruby on GitHub and attend RubyConf/RailsConf to stay current with community direction.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
