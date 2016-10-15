function pad(n, width, z) {
   z = z || '0';
   n = n + '';
   return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function countdown(time, id) {
   t = time;
   d = Math.floor(t / (60 * 60 * 24)) % 24;
   h = Math.floor(t / (60 * 60)) % 24;
   m = Math.floor(t / 60) % 60;

   s = t % 60;
   d = (d > 0) ? d + "d " : "";
   h = (h < 10) ? "0" + h : h;
   m = (m < 10) ? "0" + m : m;
   s = (s < 10) ? "0" + s : s;

   strZeit = /*d + h + ":" + m + ":" + */s;

   if (time > 0) {
      window.setTimeout('countdown(' + --time + ',\'' + id + '\')', 1000);
   } else {
      location.reload(1);
   }
   document.getElementById(id).innerHTML = strZeit;
}

function countdown2(d, h, m, s, id)
{
   countdown(d * 60 * 60 * 24 + h * 60 * 60 + m * 60 + s, id);
}