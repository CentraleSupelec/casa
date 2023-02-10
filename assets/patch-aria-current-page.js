const activelink = $('.page-item.active')[0];
if (activelink) {
    activelink.setAttribute('aria-current', 'page');
}
