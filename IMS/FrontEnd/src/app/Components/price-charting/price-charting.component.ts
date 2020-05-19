import { Component, OnInit } from '@angular/core';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';
import { CookieService } from 'ngx-cookie-service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-price-charting',
  templateUrl: './price-charting.component.html',
  styleUrls: ['./price-charting.component.css']
})
export class PriceChartingComponent implements OnInit {
  name = 'Price Charting iFrame';
  url: string = "https://www.pricecharting.com/";
  urlSafe: SafeResourceUrl;


  constructor(private cookies: CookieService, private router: Router, public sanitizer: DomSanitizer) { }

  ngOnInit() {
    if(this.cookies.get('loggedIn') != 'true'){       
      this.router.navigate(['/login']);
    }
    this.urlSafe= this.sanitizer.bypassSecurityTrustResourceUrl(this.url);
  }

}
