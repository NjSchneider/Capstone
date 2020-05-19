import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PriceChartingComponent } from './price-charting.component';

describe('PriceChartingComponent', () => {
  let component: PriceChartingComponent;
  let fixture: ComponentFixture<PriceChartingComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PriceChartingComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PriceChartingComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
