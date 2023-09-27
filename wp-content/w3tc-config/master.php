<?php exit; ?>{
    "version": "0.9.7.3",
    "cluster.messagebus.debug": false,
    "cluster.messagebus.enabled": false,
    "cluster.messagebus.sns.region": "",
    "cluster.messagebus.sns.api_key": "",
    "cluster.messagebus.sns.api_secret": "",
    "cluster.messagebus.sns.topic_arn": "",
    "dbcache.configuration_overloaded": false,
    "dbcache.debug": "0",
    "dbcache.enabled": "0",
    "dbcache.engine": "redis",
    "dbcache.file.gc": 3600,
    "dbcache.file.locking": false,
    "dbcache.lifetime": 180,
    "dbcache.memcached.persistent": true,
    "dbcache.memcached.aws_autodiscovery": false,
    "dbcache.memcached.servers": [
        "127.0.0.1:11211"
    ],
    "dbcache.memcached.username": "",
    "dbcache.memcached.password": "",
    "dbcache.redis.persistent": true,
    "dbcache.redis.servers": [
        "127.0.0.1:6379"
    ],
    "dbcache.redis.password": "",
    "dbcache.redis.dbid": 0,
    "dbcache.use_filters": false,
    "dbcache.reject.constants": [
        "APP_REQUEST",
        "DOING_CRON",
        "DONOTCACHEDB",
        "SHORTINIT",
        "XMLRPC_REQUEST"
    ],
    "dbcache.reject.cookie": [],
    "dbcache.reject.logged": true,
    "dbcache.reject.sql": [
        "gdsr_",
        "wp_rg_",
        "_wp_session_",
        "_wc_session_"
    ],
    "dbcache.reject.uri": [],
    "dbcache.reject.words": [
        "^\\s*insert\\b",
        "^\\s*delete\\b",
        "^\\s*update\\b",
        "^\\s*replace\\b",
        "^\\s*create\\b",
        "^\\s*alter\\b",
        "^\\s*show\\b",
        "^\\s*set\\b",
        "\\bautoload\\s+=\\s+'yes'",
        "\\bsql_calc_found_rows\\b",
        "\\bfound_rows\\(\\)"
    ],
    "objectcache.configuration_overloaded": false,
    "objectcache.enabled": "1",
    "objectcache.debug": "0",
    "objectcache.enabled_for_wp_admin": true,
    "objectcache.fallback_transients": true,
    "objectcache.engine": "redis",
    "objectcache.file.gc": 3600,
    "objectcache.file.locking": false,
    "objectcache.memcached.servers": [
        "127.0.0.1:11211"
    ],
    "objectcache.memcached.persistent": true,
    "objectcache.memcached.aws_autodiscovery": false,
    "objectcache.memcached.username": "",
    "objectcache.memcached.password": "",
    "objectcache.redis.persistent": true,
    "objectcache.redis.servers": [
        "127.0.0.1:6379"
    ],
    "objectcache.redis.password": "",
    "objectcache.redis.dbid": 0,
    "objectcache.groups.global": [
        "users",
        "userlogins",
        "usermeta",
        "user_meta",
        "site-transient",
        "site-options",
        "site-lookup",
        "blog-lookup",
        "blog-details",
        "rss",
        "global-posts"
    ],
    "objectcache.groups.nonpersistent": [
        "comment",
        "counts",
        "plugins"
    ],
    "objectcache.lifetime": 180,
    "objectcache.purge.all": false,
    "pgcache.configuration_overloaded": false,
    "pgcache.enabled": "1",
    "pgcache.comment_cookie_ttl": "1800",
    "pgcache.debug": "0",
    "pgcache.engine": "redis",
    "pgcache.file.gc": "3600",
    "pgcache.file.nfs": false,
    "pgcache.file.locking": false,
    "pgcache.lifetime": "3600",
    "pgcache.memcached.servers": [
        "127.0.0.1:11211"
    ],
    "pgcache.memcached.persistent": true,
    "pgcache.memcached.aws_autodiscovery": false,
    "pgcache.memcached.username": "",
    "pgcache.memcached.password": "",
    "pgcache.redis.persistent": "1",
    "pgcache.redis.servers": [
        "127.0.0.1:6379"
    ],
    "pgcache.redis.password": "",
    "pgcache.redis.dbid": "0",
    "pgcache.cache.query": "0",
    "pgcache.cache.home": "1",
    "pgcache.cache.feed": "1",
    "pgcache.cache.nginx_handle_xml": "0",
    "pgcache.cache.ssl": "1",
    "pgcache.cache.404": "0",
    "pgcache.cache.headers": [
        "Last-Modified",
        "Content-Type",
        "X-Pingback",
        "P3P",
        "Link"
    ],
    "pgcache.compatibility": "0",
    "pgcache.remove_charset": "0",
    "pgcache.accept.uri": [
        "sitemap(_index)?\\.xml(\\.gz)?",
        "([a-z0-9_\\-]+)?sitemap\\.xsl",
        "[a-z0-9_\\-]+-sitemap([0-9]+)?\\.xml(\\.gz)?"
    ],
    "pgcache.accept.files": [
        "wp-comments-popup.php",
        "wp-links-opml.php",
        "wp-locations.php"
    ],
    "pgcache.accept.qs": [
        ""
    ],
    "pgcache.late_init": "0",
    "pgcache.late_caching": "0",
    "pgcache.mirrors.enabled": "0",
    "pgcache.mirrors.home_urls": [
        ""
    ],
    "pgcache.reject.front_page": "0",
    "pgcache.reject.logged": "1",
    "pgcache.reject.logged_roles": "1",
    "pgcache.reject.roles": [
        "administrator",
        "editor"
    ],
    "pgcache.reject.uri": [
        "wp-.*\\.php",
        "index\\.php",
        "\/careers\/"
    ],
    "pgcache.reject.categories": [
        ""
    ],
    "pgcache.reject.tags": [
        ""
    ],
    "pgcache.reject.authors": [
        ""
    ],
    "pgcache.reject.custom": [
        ""
    ],
    "pgcache.reject.ua": [
        ""
    ],
    "pgcache.reject.cookie": [
        "wptouch_switch_toggle"
    ],
    "pgcache.reject.request_head": false,
    "pgcache.purge.front_page": "1",
    "pgcache.purge.home": "1",
    "pgcache.purge.post": "1",
    "pgcache.purge.comments": "0",
    "pgcache.purge.author": "0",
    "pgcache.purge.terms": "0",
    "pgcache.purge.archive.daily": "0",
    "pgcache.purge.archive.monthly": "0",
    "pgcache.purge.archive.yearly": "0",
    "pgcache.purge.feed.blog": "1",
    "pgcache.purge.feed.comments": "0",
    "pgcache.purge.feed.author": "0",
    "pgcache.purge.feed.terms": "0",
    "pgcache.purge.feed.types": [
        "rss2"
    ],
    "pgcache.purge.postpages_limit": "10",
    "pgcache.purge.pages": [
        ""
    ],
    "pgcache.purge.sitemap_regex": "([a-z0-9_\\-]*?)sitemap([a-z0-9_\\-]*)?\\.xml",
    "pgcache.prime.enabled": "1",
    "pgcache.prime.interval": "1800",
    "pgcache.prime.limit": "50",
    "pgcache.prime.sitemap": "",
    "pgcache.prime.post.enabled": "1",
    "pgcache.rest": "",
    "pgcache.cookiegroups.enabled": false,
    "pgcache.cookiegroups.groups": {
        "mobile": {
            "enabled": false,
            "cache": true,
            "cookies": [
                "wptouch-pro-view=mobile",
                "wptouch-pro-cache-state=mobile"
            ]
        },
        "loggedin": {
            "enabled": false,
            "cache": true,
            "cookies": [
                "wordpress_logged_in_.*"
            ]
        },
        "subscribers": {
            "enabled": false,
            "cache": true,
            "cookies": [
                "role=subscriber",
                "role=member"
            ]
        }
    },
    "stats.enabled": "0",
    "minify.configuration_overloaded": false,
    "minify.enabled": "0",
    "minify.auto": "1",
    "minify.debug": "0",
    "minify.engine": "redis",
    "minify.error.notification": "",
    "minify.file.gc": 86400,
    "minify.file.nfs": false,
    "minify.file.locking": false,
    "minify.memcached.servers": [
        "127.0.0.1:11211"
    ],
    "minify.memcached.persistent": true,
    "minify.memcached.aws_autodiscovery": false,
    "minify.memcached.username": "",
    "minify.memcached.password": "",
    "minify.redis.persistent": true,
    "minify.redis.servers": [
        "127.0.0.1:6379"
    ],
    "minify.redis.password": "",
    "minify.redis.dbid": 0,
    "minify.rewrite": true,
    "minify.options": [],
    "minify.symlinks": [],
    "minify.lifetime": 86400,
    "minify.upload": true,
    "minify.html.enable": false,
    "minify.html.engine": "html",
    "minify.html.reject.feed": false,
    "minify.html.inline.css": false,
    "minify.html.inline.js": false,
    "minify.html.strip.crlf": false,
    "minify.html.comments.ignore": [
        "google_ad_",
        "RSPEAK_"
    ],
    "minify.css.combine": false,
    "minify.css.enable": true,
    "minify.css.engine": "yuicss",
    "minify.css.http2push": false,
    "minify.css.strip.comments": false,
    "minify.css.strip.crlf": false,
    "minify.css.embed": false,
    "minify.css.imports": "",
    "minify.css.groups": [],
    "minify.js.http2push": false,
    "minify.js.enable": true,
    "minify.js.engine": "yuijs",
    "minify.js.combine.header": false,
    "minify.js.header.embed_type": "blocking",
    "minify.js.combine.body": false,
    "minify.js.body.embed_type": "blocking",
    "minify.js.combine.footer": false,
    "minify.js.footer.embed_type": "blocking",
    "minify.js.strip.comments": false,
    "minify.js.strip.crlf": false,
    "minify.js.groups": [],
    "minify.yuijs.path.java": "java",
    "minify.yuijs.path.jar": "yuicompressor.jar",
    "minify.yuijs.options.line-break": 5000,
    "minify.yuijs.options.nomunge": false,
    "minify.yuijs.options.preserve-semi": false,
    "minify.yuijs.options.disable-optimizations": false,
    "minify.yuicss.path.java": "java",
    "minify.yuicss.path.jar": "yuicompressor.jar",
    "minify.yuicss.options.line-break": 5000,
    "minify.ccjs.path.java": "java",
    "minify.ccjs.path.jar": "compiler.jar",
    "minify.ccjs.options.compilation_level": "SIMPLE_OPTIMIZATIONS",
    "minify.ccjs.options.formatting": "",
    "minify.csstidy.options.remove_bslash": true,
    "minify.csstidy.options.compress_colors": false,
    "minify.csstidy.options.compress_font-weight": false,
    "minify.csstidy.options.lowercase_s": false,
    "minify.csstidy.options.optimise_shorthands": 0,
    "minify.csstidy.options.remove_last_;": false,
    "minify.csstidy.options.remove_space_before_important": false,
    "minify.csstidy.options.case_properties": 1,
    "minify.csstidy.options.sort_properties": false,
    "minify.csstidy.options.sort_selectors": false,
    "minify.csstidy.options.merge_selectors": 0,
    "minify.csstidy.options.discard_invalid_selectors": false,
    "minify.csstidy.options.discard_invalid_properties": false,
    "minify.csstidy.options.css_level": "CSS3.0",
    "minify.csstidy.options.preserve_css": false,
    "minify.csstidy.options.timestamp": false,
    "minify.csstidy.options.template": "highest_compression",
    "minify.htmltidy.options.clean": false,
    "minify.htmltidy.options.hide-comments": true,
    "minify.htmltidy.options.wrap": 0,
    "minify.reject.logged": false,
    "minify.reject.ua": [],
    "minify.reject.uri": [],
    "minify.reject.files.js": [],
    "minify.reject.files.css": [],
    "minify.cache.files": [
        ""
    ],
    "minify.cache.files_regexp": false,
    "cdn.configuration_overloaded": false,
    "cdn.enabled": "0",
    "cdn.debug": "0",
    "cdn.flush_manually": "1",
    "cdn.engine": "cf",
    "cdn.uploads.enable": "1",
    "cdn.includes.enable": "1",
    "cdn.includes.files": "*.css;*.js;*.gif;*.png;*.jpg;*.xml",
    "cdn.theme.enable": "1",
    "cdn.theme.files": "*.css;*.js;*.gif;*.png;*.jpg;*.ico;*.ttf;*.otf;*.woff;*.woff2;*.less",
    "cdn.minify.enable": true,
    "cdn.custom.enable": "1",
    "cdn.custom.files": [
        "",
        "app\/*"
    ],
    "cdn.import.files": "",
    "cdn.queue.interval": "900",
    "cdn.queue.limit": "25",
    "cdn.force.rewrite": "1",
    "cdn.autoupload.enabled": "1",
    "cdn.autoupload.interval": "3600",
    "cdn.canonical_header": "0",
    "cdn.admin.media_library": "0",
    "cdn.cors_header": "1",
    "cdn.ftp.host": "",
    "cdn.ftp.type": "",
    "cdn.ftp.user": "",
    "cdn.ftp.pass": "",
    "cdn.ftp.path": "",
    "cdn.ftp.pasv": false,
    "cdn.ftp.domain": [],
    "cdn.ftp.ssl": "auto",
    "cdn.ftp.default_keys": true,
    "cdn.ftp.pubkey": "",
    "cdn.ftp.privkey": "",
    "cdn.google_drive.client_id": "",
    "cdn.google_drive.refresh_token": "",
    "cdn.google_drive.folder.id": "",
    "cdn.google_drive.folder.title": "",
    "cdn.google_drive.folder.url": "",
    "cdn.highwinds.account_hash": "",
    "cdn.highwinds.api_token": "",
    "cdn.highwinds.host.hash_code": "",
    "cdn.highwinds.host.domains": [],
    "cdn.highwinds.ssl": "auto",
    "cdn.s3.key": "AKIAU35D7MC3JGCCB2XS",
    "cdn.s3.secret": "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",
    "cdn.s3.bucket": "policies365uat",
    "cdn.s3.bucket.location": "ap-south-1",
    "cdn.s3.cname": [],
    "cdn.s3.ssl": "auto",
    "cdn.s3_compatible.api_host": "auto",
    "cdn.cf.key": "AKIAU35D7MC3JGCCB2XS",
    "cdn.cf.secret": "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",
    "cdn.cf.bucket": "uat.policies365.com",
    "cdn.cf.bucket.location": "ap-south-1",
    "cdn.cf.id": "d19b513mhkwx7a",
    "cdn.cf.cname": [
        "cdnuat.policies365.com"
    ],
    "cdn.cf.ssl": "auto",
    "cdn.cf2.key": "AKIAU35D7MC3JGCCB2XS",
    "cdn.cf2.secret": "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",
    "cdn.cf2.id": "devllk6b2k2u7",
    "cdn.cf2.cname": [],
    "cdn.cf2.ssl": "auto",
    "cdn.rscf.user": "",
    "cdn.rscf.key": "",
    "cdn.rscf.location": "us",
    "cdn.rscf.container": "",
    "cdn.rscf.cname": [],
    "cdn.rscf.ssl": "auto",
    "cdn.rackspace_cdn.user_name": "",
    "cdn.rackspace_cdn.api_key": "",
    "cdn.rackspace_cdn.region": "",
    "cdn.rackspace_cdn.service.access_url": "",
    "cdn.rackspace_cdn.service.id": "",
    "cdn.rackspace_cdn.service.name": "",
    "cdn.rackspace_cdn.service.protocol": "http",
    "cdn.rackspace_cdn.domains": [],
    "cdn.azure.user": "",
    "cdn.azure.key": "",
    "cdn.azure.container": "",
    "cdn.azure.cname": [],
    "cdn.azure.ssl": "auto",
    "cdn.mirror.domain": [],
    "cdn.mirror.ssl": "auto",
    "cdn.limelight.short_name": "",
    "cdn.limelight.username": "",
    "cdn.limelight.api_key": "",
    "cdn.limelight.host.domains": [],
    "cdn.limelight.ssl": "auto",
    "cdn.maxcdn.authorization_key": "",
    "cdn.maxcdn.domain": [],
    "cdn.maxcdn.ssl": "auto",
    "cdn.maxcdn.zone_id": 0,
    "cdn.cotendo.username": "",
    "cdn.cotendo.password": "",
    "cdn.cotendo.zones": [],
    "cdn.cotendo.domain": [],
    "cdn.cotendo.ssl": "auto",
    "cdn.akamai.username": "",
    "cdn.akamai.password": "",
    "cdn.akamai.email_notification": [],
    "cdn.akamai.action": "invalidate",
    "cdn.akamai.zone": "production",
    "cdn.akamai.domain": [],
    "cdn.akamai.ssl": "auto",
    "cdn.edgecast.account": "",
    "cdn.edgecast.token": "",
    "cdn.edgecast.domain": [],
    "cdn.edgecast.ssl": "auto",
    "cdn.att.account": "",
    "cdn.att.token": "",
    "cdn.att.domain": [],
    "cdn.att.ssl": "auto",
    "cdn.stackpath.authorization_key": "",
    "cdn.stackpath.domain": [],
    "cdn.stackpath.ssl": "auto",
    "cdn.stackpath.zone_id": 0,
    "cdn.stackpath2.client_id": "",
    "cdn.stackpath2.client_secret": "",
    "cdn.stackpath2.stack_id": "",
    "cdn.stackpath2.site_id": 0,
    "cdn.stackpath2.site_root_domain": 0,
    "cdn.stackpath2.domain": [],
    "cdn.stackpath2.ssl": "auto",
    "cdn.reject.admins": false,
    "cdn.reject.logged_roles": "1",
    "cdn.reject.roles": [
        "administrator"
    ],
    "cdn.reject.ua": [
        ""
    ],
    "cdn.reject.uri": [
        ""
    ],
    "cdn.reject.files": [
        "{uploads_dir}\/wpcf7_captcha\/*",
        "{uploads_dir}\/imagerotator.swf",
        "{plugins_dir}\/wp-fb-autoconnect\/facebook-platform\/channel.html"
    ],
    "cdn.reject.ssl": "0",
    "cdnfsd.enabled": "0",
    "cdnfsd.engine": "",
    "cdnfsd.debug": false,
    "cdnfsd.cloudfront.access_key": "",
    "cdnfsd.cloudfront.secret_key": "",
    "cdnfsd.cloudfront.distribution_id": "",
    "cdnfsd.limelight.short_name": "",
    "cdnfsd.limelight.username": "",
    "cdnfsd.limelight.api_key": "",
    "cdnfsd.maxcdn.api_key": "",
    "cdnfsd.maxcdn.zone_id": 0,
    "cdnfsd.stackpath.api_key": "",
    "cdnfsd.stackpath.zone_id": 0,
    "cdnfsd.stackpath2.client_id": "",
    "cdnfsd.stackpath2.client_secret": "",
    "cdnfsd.stackpath2.stack_id": "",
    "cdnfsd.stackpath2.site_id": 0,
    "cdnfsd.stackpath2.site_root_domain": 0,
    "cdnfsd.stackpath2.domain": [],
    "cdnfsd.stackpath2.ssl": "auto",
    "varnish.configuration_overloaded": false,
    "varnish.enabled": "0",
    "varnish.debug": false,
    "varnish.servers": [
        ""
    ],
    "browsercache.configuration_overloaded": false,
    "browsercache.enabled": "1",
    "browsercache.rewrite": "0",
    "browsercache.no404wp": "0",
    "browsercache.no404wp.exceptions": [
        "robots\\.txt",
        "[a-z0-9_\\-]*sitemap[a-z0-9_\\.\\-]*\\.(xml|xsl|html)(\\.gz)?"
    ],
    "browsercache.cssjs.last_modified": "1",
    "browsercache.cssjs.compression": "1",
    "browsercache.cssjs.brotli": false,
    "browsercache.cssjs.expires": "1",
    "browsercache.cssjs.lifetime": "31536000",
    "browsercache.cssjs.nocookies": "1",
    "browsercache.cssjs.cache.control": "1",
    "browsercache.cssjs.cache.policy": "cache_public_maxage",
    "browsercache.cssjs.etag": "1",
    "browsercache.cssjs.w3tc": "1",
    "browsercache.cssjs.replace": "0",
    "browsercache.cssjs.querystring": "0",
    "browsercache.html.compression": "1",
    "browsercache.html.brotli": false,
    "browsercache.html.last_modified": "1",
    "browsercache.html.expires": "1",
    "browsercache.html.lifetime": "3600",
    "browsercache.html.cache.control": "1",
    "browsercache.html.cache.policy": "cache_public_maxage",
    "browsercache.html.etag": "1",
    "browsercache.html.w3tc": "1",
    "browsercache.html.replace": false,
    "browsercache.other.last_modified": "1",
    "browsercache.other.compression": "1",
    "browsercache.other.brotli": false,
    "browsercache.other.expires": "1",
    "browsercache.other.lifetime": "31536000",
    "browsercache.other.nocookies": "1",
    "browsercache.other.cache.control": "1",
    "browsercache.other.cache.policy": "cache_public_maxage",
    "browsercache.other.etag": "1",
    "browsercache.other.w3tc": "1",
    "browsercache.other.replace": "0",
    "browsercache.other.querystring": "0",
    "browsercache.replace.exceptions": [
        ""
    ],
    "browsercache.security.session.cookie_httponly": "",
    "browsercache.security.session.cookie_secure": "",
    "browsercache.security.session.use_only_cookies": "",
    "browsercache.hsts": "0",
    "browsercache.security.hsts.directive": "maxage",
    "browsercache.security.xfo": "0",
    "browsercache.security.xfo.directive": "same",
    "browsercache.security.xfo.allow": "",
    "browsercache.security.xss": "0",
    "browsercache.security.xss.directive": "block",
    "browsercache.security.xcto": "0",
    "browsercache.security.pkp": "0",
    "browsercache.security.pkp.pin": "",
    "browsercache.security.pkp.pin.backup": "",
    "browsercache.security.pkp.extra": "maxage",
    "browsercache.security.pkp.report.url": "",
    "browsercache.security.pkp.report.only": "0",
    "browsercache.security.referrer.policy": "1",
    "browsercache.security.referrer.policy.directive": "no-referrer-when-downgrade",
    "browsercache.security.csp": "0",
    "browsercache.security.csp.base": "",
    "browsercache.security.csp.frame": "",
    "browsercache.security.csp.connect": "",
    "browsercache.security.csp.font": "",
    "browsercache.security.csp.script": "",
    "browsercache.security.csp.style": "",
    "browsercache.security.csp.img": "",
    "browsercache.security.csp.media": "",
    "browsercache.security.csp.object": "",
    "browsercache.security.csp.plugin": "",
    "browsercache.security.csp.form": "",
    "browsercache.security.csp.frame.ancestors": "",
    "browsercache.security.csp.sandbox": "",
    "browsercache.security.csp.default": "",
    "mobile.configuration_overloaded": false,
    "mobile.enabled": false,
    "mobile.rgroups": {
        "tablets": {
            "theme": "",
            "enabled": false,
            "redirect": "",
            "agents": [
                "a1-32ab0",
                "a210",
                "a211",
                "b6000-h",
                "b8000-h",
                "bnrv200",
                "bntv400",
                "darwin",
                "gt-n8005",
                "gt-p3105",
                "gt-p6810",
                "gt-p7510",
                "hmj37",
                "hp-tablet",
                "hp\\sslate",
                "hp\\sslatebook",
                "ht7s3",
                "ideatab_a1107",
                "ideataba2109a",
                "ideos\\ss7",
                "imm76d",
                "ipad",
                "k00f",
                "kfjwi",
                "kfot",
                "kftt",
                "kindle",
                "l-06c",
                "lg-f200k",
                "lg-f200l",
                "lg-f200s",
                "m470bsa",
                "m470bse",
                "maxwell",
                "me173x",
                "mediapad",
                "midc497",
                "msi\\senjoy\\s10\\splus",
                "mz601",
                "mz616",
                "nexus",
                "nookcolor",
                "pg09410",
                "pg41200",
                "pmp5570c",
                "pmp5588c",
                "pocketbook",
                "qmv7a",
                "sgp311",
                "sgpt12",
                "shv-e230k",
                "shw-m305w",
                "shw-m380w",
                "sm-p605",
                "smarttab",
                "sonysgp321",
                "sph-p500",
                "surfpad",
                "tab07-200",
                "tab10-201",
                "tab465euk",
                "tab474",
                "tablet",
                "tegranote",
                "tf700t",
                "thinkpad",
                "viewpad",
                "voltaire"
            ]
        },
        "phones": {
            "theme": "",
            "enabled": false,
            "redirect": "",
            "agents": [
                "(android|bb\\d+|meego).+mobile",
                "240x320",
                "2.0\\ mmp",
                "\\bppc\\b",
                "acer\\ s100",
                "alcatel",
                "amoi",
                "archos5",
                "asus",
                "au-mic",
                "audiovox",
                "avantgo",
                "bada",
                "benq",
                "bird",
                "blackberry",
                "blazer",
                "cdm",
                "cellphone",
                "cupcake",
                "danger",
                "ddipocket",
                "docomo",
                "docomo\\ ht-03a",
                "dopod",
                "dream",
                "elaine\/3.0",
                "ericsson",
                "eudoraweb",
                "fly",
                "froyo",
                "googlebot-mobile",
                "haier",
                "hiptop",
                "hp.ipaq",
                "htc",
                "htc\\ hero",
                "htc\\ magic",
                "htc_dream",
                "htc_magic",
                "huawei",
                "i-mobile",
                "iemobile",
                "iemobile\/7",
                "iemobile\/7.0",
                "iemobile\/9",
                "incognito",
                "iphone",
                "ipod",
                "j-phone",
                "kddi",
                "konka",
                "kwc",
                "kyocera\/wx310k",
                "lenovo",
                "lg",
                "lg\/u990",
                "lg-gw620",
                "lge\\ vx",
                "liquid\\ build",
                "maemo",
                "midp",
                "midp-2.0",
                "mmef20",
                "mmp",
                "mobilephone",
                "mot-mb200",
                "mot-mb300",
                "mot-v",
                "motorola",
                "msie\\ 10.0",
                "netfront",
                "newgen",
                "newt",
                "nexus\\ 7",
                "nexus\\ one",
                "nintendo\\ ds",
                "nintendo\\ wii",
                "nitro",
                "nokia",
                "novarra",
                "openweb",
                "opera\\ mini",
                "opera\\ mobi",
                "opera.mobi",
                "p160u",
                "palm",
                "panasonic",
                "pantech",
                "pdxgw",
                "pg",
                "philips",
                "phone",
                "playbook",
                "playstation\\ portable",
                "portalmmm",
                "proxinet",
                "psp",
                "qtek",
                "s8000",
                "sagem",
                "samsung",
                "samsung-s8000",
                "sanyo",
                "sch",
                "sch-i800",
                "sec",
                "sendo",
                "series60.*webkit",
                "series60\/5.0",
                "sgh",
                "sharp",
                "sharp-tq-gx10",
                "small",
                "smartphone",
                "softbank",
                "sonyericsson",
                "sonyericssone10",
                "sonyericssonu20",
                "sonyericssonx10",
                "sph",
                "symbian",
                "symbian\\ os",
                "symbianos",
                "t-mobile\\ mytouch\\ 3g",
                "t-mobile\\ opal",
                "tattoo",
                "toshiba",
                "touch",
                "treo",
                "ts21i-10",
                "up.browser",
                "up.link",
                "uts",
                "vertu",
                "vodafone",
                "wap",
                "webmate",
                "webos",
                "willcome",
                "windows\\ ce",
                "windows.ce",
                "winwap",
                "xda",
                "xoom",
                "zte"
            ]
        }
    },
    "referrer.configuration_overloaded": false,
    "referrer.enabled": false,
    "referrer.rgroups": {
        "search_engines": {
            "theme": "",
            "enabled": false,
            "redirect": "",
            "referrers": [
                "google\\.com",
                "yahoo\\.com",
                "bing\\.com",
                "ask\\.com",
                "msn\\.com"
            ]
        }
    },
    "common.support": "",
    "common.track_usage": "0",
    "common.tweeted": false,
    "config.check": "1",
    "config.path": "",
    "widget.latest.items": 3,
    "widget.latest_news.items": 5,
    "widget.pagespeed.enabled": "1",
    "widget.pagespeed.key": "",
    "widget.pagespeed.key.restrict.referrer": "",
    "widget.pagespeed.show_in_admin_bar": "0",
    "timelimit.email_send": 180,
    "timelimit.varnish_purge": 300,
    "timelimit.cache_flush": 600,
    "timelimit.cache_gc": 600,
    "timelimit.cdn_upload": 600,
    "timelimit.cdn_delete": 300,
    "timelimit.cdn_purge": 300,
    "timelimit.cdn_import": 600,
    "timelimit.cdn_test": 300,
    "timelimit.cdn_container_create": 300,
    "timelimit.domain_rename": 120,
    "timelimit.minify_recommendations": 600,
    "common.instance_id": 1511960261,
    "common.force_master": true,
    "extensions.active": {
        "newrelic": "w3-total-cache\/Extension_NewRelic_Plugin.php",
        "fragmentcache": "w3-total-cache\/Extension_FragmentCache_Plugin.php"
    },
    "extensions.active_frontend": [],
    "plugin.license_key": "",
    "plugin.type": "",
    "fragmentcache": {
        "engine": "redis"
    },
    "notes.need_empty_fragmentcache": false
}