/**
 * CodeFoundry Site Configuration
 *
 * Hack (HHVM) typed configuration for the CodeFoundry website.
 * Run with HHVM: hhvm -f lib/SiteConfig.hack
 */

namespace CodeFoundry\Config;

use type Facebook\TypeAssert\TypeAssert;

/**
 * Immutable value type representing a navigation link.
 */
final class NavLink {
  public function __construct(
    public readonly string $label,
    public readonly string $href,
    public readonly string $id,
  ) {}
}

/**
 * Immutable value type representing a footer link column.
 */
final class FooterColumn {
  public function __construct(
    public readonly string $title,
    public readonly vec<NavLink> $links,
  ) {}
}

/**
 * Immutable value type representing contact/social info.
 */
final class SocialLink {
  public function __construct(
    public readonly string $label,
    public readonly string $href,
    public readonly string $icon,
  ) {}
}

/**
 * Central, typed site configuration loaded from navigation.json.
 */
final class SiteConfig {

  private static ?SiteConfig $instance = null;

  private string $siteName;
  private string $tagline;
  private string $email;
  private string $phone;
  private string $address;
  private string $copyright;
  private vec<NavLink> $navLinks;
  private vec<FooterColumn> $footerColumns;
  private vec<SocialLink> $socialLinks;

  private function __construct(dict<string, mixed> $data) {
    $site = $data['site'] as dict<_, _>;
    $this->siteName  = (string)$site['name'];
    $this->tagline   = (string)$site['tagline'];
    $this->email     = (string)$site['email'];
    $this->phone     = (string)$site['phone'];
    $this->address   = (string)$site['address'];
    $this->copyright = (string)$site['copyright'];

    // Build navigation links
    $navRaw = $data['nav'] as vec<_>;
    $this->navLinks = Vec\map($navRaw, $item ==> {
      $link = $item as dict<_, _>;
      return new NavLink(
        (string)$link['label'],
        (string)$link['href'],
        (string)$link['id'],
      );
    });

    // Build footer columns
    $footerRaw = $data['footer'] as dict<_, _>;
    $columns = vec[
      tuple('Services',  'services'),
      tuple('Company',   'company'),
      tuple('Resources', 'resources'),
    ];
    $this->footerColumns = Vec\map($columns, $col ==> {
      list($title, $key) = $col;
      $items = ($footerRaw[$key] ?? vec[]) as vec<_>;
      $links = Vec\map($items, $item ==> {
        $link = $item as dict<_, _>;
        return new NavLink(
          (string)$link['label'],
          (string)$link['href'],
          '',
        );
      });
      return new FooterColumn($title, $links);
    });

    // Build social links
    $socialRaw = ($footerRaw['social'] ?? vec[]) as vec<_>;
    $this->socialLinks = Vec\map($socialRaw, $item ==> {
      $link = $item as dict<_, _>;
      return new SocialLink(
        (string)$link['label'],
        (string)$link['href'],
        (string)$link['icon'],
      );
    });
  }

  /**
   * Load configuration from navigation.json (singleton).
   */
  public static function getInstance(string $jsonPath = ''): SiteConfig {
    if (self::$instance is null) {
      if ($jsonPath === '') {
        $jsonPath = \dirname(__DIR__).'/data/navigation.json';
      }
      $raw  = \file_get_contents($jsonPath);
      if ($raw === false) {
        throw new \RuntimeException('Cannot read navigation.json at: '.$jsonPath);
      }
      $data = \json_decode($raw, true) as dict<_, _>;
      self::$instance = new SiteConfig($data);
    }
    return self::$instance;
  }

  public function getSiteName(): string  { return $this->siteName; }
  public function getTagline(): string   { return $this->tagline; }
  public function getEmail(): string     { return $this->email; }
  public function getPhone(): string     { return $this->phone; }
  public function getAddress(): string   { return $this->address; }
  public function getCopyright(): string { return $this->copyright; }

  public function getNavLinks(): vec<NavLink>         { return $this->navLinks; }
  public function getFooterColumns(): vec<FooterColumn> { return $this->footerColumns; }
  public function getSocialLinks(): vec<SocialLink>   { return $this->socialLinks; }

  /**
   * Return the human-readable page title for a given active-page ID.
   */
  public function getPageTitle(string $activeId): string {
    foreach ($this->navLinks as $link) {
      if ($link->id === $activeId) {
        return $link->label;
      }
    }
    return $this->siteName;
  }
}
