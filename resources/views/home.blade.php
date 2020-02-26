@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/algoliasearch@4.0.0/dist/algoliasearch-lite.umd.js" integrity="sha256-MfeKq2Aw9VAkaE9Caes2NOxQf6vUa8Av0JqcUXUGkd0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.0.0/dist/instantsearch.production.min.js" integrity="sha256-6S7q0JJs/Kx4kb/fv0oMjS855QTz5Rc2hh9AkIUjUsk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/reset-min.css" integrity="sha256-t2ATOGCtAIZNnzER679jwcFcKYfLlw01gli6F6oszk8=" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/instantsearch.css@7.3.1/themes/algolia-min.css" integrity="sha256-HB49n/BZjuqiCtQQf49OdZn63XuKFaxcIHWf0HNKte8=" crossorigin="anonymous">

<div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-globe text-warning"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Passengers</p>
                      <p class="card-title">33
                        </p><p>
                    </p></div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <i class="fa fa-refresh"></i> Today
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-money-coins text-success"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Revenue</p>
                      <p class="card-title">1.345 &euro;
                        </p><p>
                    </p></div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <i class="fa fa-calendar-o"></i> Today
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-send text-danger"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Available Slots</p>
                      <p class="card-title">10
                        </p><p>
                    </p></div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <i class="fa fa-clock-o"></i> In the last hour
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-umbrella-13 text-primary"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Hours Flown</p>
                      <p class="card-title">15
                        </p><p>
                    </p></div>
                  </div>
                </div>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <i class="fa fa-refresh"></i> Update now
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="ais-InstantSearch">
  <div class="right-panel">
    <div id="searchbox"></div>
    <div id="hits" style="margin-top: 20px;"></div>
  </div>
</div>
<script>
const searchClient = algoliasearch('9XRNPC6UIT', '4dbd0e04e9e4a769b4de2caf3062475d');

const search = instantsearch({
  indexName: 'item',
  searchClient,
});

// 1. Create a render function
const renderHits = (renderOptions, isFirstRender) => {
    const {
        hits,
        results,
        widgetParams,
    } = renderOptions;

    console.log(hits);

    document.querySelector('#hits').innerHTML = `
        <div class="row">
          ${hits
            .map(
              (item) => {
              let subtitle;

              if (item.entity === "App\\Aircraft") {
                    subtitle = "<small>Aircraft</small>";
                } else if (item.entity === "App\\User") {
                    subtitle = "<small>User</small>";
                } else if (item.entity === "App\\Slot") {
                    subtitle = "<small>Slot</small>";
                } else if (item.entity === "App\\Booking") {
                    subtitle = "<small>Booking</small>";
                } else if (item.entity === "App\\Ticket") {
                    subtitle = "<small>Ticket</small>";
                }
              return `<div class="col-sm-3">
                <div class="card">
  <div class="card-body">
     ${ item.title }<br />
     <small>${ subtitle }</small>
  </div>
</div></div>`;
}
            )
            .join('')}  </div>
      `;
}

// 2. Create the custom widget
const customHits = instantsearch.connectors.connectHits(
  renderHits
);

search.addWidgets([
  instantsearch.widgets.searchBox({
    container: '#searchbox',
    placeholder: 'Search for Flights, Bookings, Tickets, Pilots',
    autofocus: true,
    queryHook(query, search) {
        console.log('searching');
        search(query);
      },
  }),

  customHits({
  })
]);

search.start();
</script>
@endsection

