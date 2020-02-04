<template>
    <div>
        <div class="spinner" v-if="state=='loading'">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
        <div id="strip"></div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                state: "loading",
                data: null
            }
        },
        methods: {
            initStrip: function() {
                var draw = SVG().addTo('#strip').size('100%', '100%');
                let options = { hour:"2-digit", minute:"2-digit" };

                // define colors for later fades
                let gradientWhite = draw.gradient('linear', function(add) {
                  add.stop({ offset: 0.6, color: '#fff', opacity: 1 })
                  add.stop({ offset: 1, color: '#fff', opacity: 0 })
                });
                let gradient = draw.gradient('linear', function(add) {
                  add.stop({ offset: 0.6, color: '#eee', opacity: 1 })
                  add.stop({ offset: 1, color: '#eee', opacity: 0 })
                });

                // draw the rows
                let y = 20;
                for(let i = 0; i < this.data.length; i++) {
                    if (i%2 == 0) {
                        draw.rect("100%", 30).move(0, y+(i*30)+20).addClass('row');
                    }
                }

                var group = draw.group();

                // draw the hour lines + text
                for(let i = 1; i < 48; i++) {
                    let hour = (i-1)%24;
                    if (hour < 10) {
                        hour = "0"+hour;
                    }
                    group.line(i*150, 0, i*150, 150).stroke({ color: '#ddd', width: 1, linecap: 'round' });
                    group.plain(hour+" local").move((i*150)+5, 20).font({
                      family:   'Arial',
                      size:     10,
                      anchor:   'left',
                      fill: '#000'
                    });
                }

                let callsigns = [];
                for(let i = 0; i < this.data.length; i++) {
                    callsigns.push(this.data[i].callsign);

                    let slots = this.data[i].slots;
                    for(let j = 0; j < slots.length; j++) {
                        let start = new Date(slots[j].starts_on);
                        let end = new Date(slots[j].ends_on);
                        let itemOffset = (start.getTime()%86400000)/86400000;
                        let itemDuration = (((end-start)/1000)/3600);

                        let el = group.rect(itemDuration*150, 20).radius(4).move((itemOffset*24*150+300), (y+(i*30)+25)).addClass('slot-'+slots[j].status);

                        if(slots[j].status == "available") {
                            //el.attr('href', bookingUrl+"?slot_id="+slots[j].id);
                            el.click(() => {
                                window.location.href = "https://192.168.178.26/booking-system/bookings/create?slot_id="+slots[j].id;
                            });
                        }
                    }
                }

                let offset = ((new Date()).getTime()%86400000)/86400000;
                group.transform({
                  positionX: ((offset*-24*150)+50),
                  //positionX: -2500,
                  origin: 'top left'
                })

                setInterval(() => {
                    let offset = ((new Date()).getTime()%86400000)/86400000;
                    group.transform({
                      positionX: ((offset*-24*150)+50),
                      origin: 'top left'
                    });
                    clock.node.textContent=(new Date()).toLocaleString("de-DE", options);
                }, 1000);


                draw.rect(150, 40).move(0, 0).fill(gradientWhite);
                for(let i = 0; i < callsigns.length; i++) {
                    if (i % 2 == 0) {
                        draw.rect(200, 30).move(0, y+(i*30)+20).fill(gradient);
                    } else {
                        draw.rect(200, 30).move(0, y+(i*30)+20).fill(gradientWhite);
                    }
                    text = draw.plain(callsigns[i]).move(5, y+(i*30)+27).font({
                      family:   'Arial',
                      size:     14,
                      anchor:   'left',
                      fill: '#000'
                    });
                }

                draw.line(200, 0, 200, 150).stroke({ color: '#666', width: 1.5, linecap: 'round' });

                draw.rect(46, 18).move(177, 0).fill("#666");
                let clock = draw.plain((new Date()).toLocaleString("de-DE", options)).move(182, 1).font({
                    family:   'Arial',
                    size:     14,
                    anchor:   'center',
                    fill: '#fff'
                });
            }
        },
        mounted: function() {
            axios.get('/booking-system/api/slots')
                .then((response) => {
                    this.state = "done";
                    this.data = response.data;
                    this.initStrip();
                });
        }
    }
</script>
