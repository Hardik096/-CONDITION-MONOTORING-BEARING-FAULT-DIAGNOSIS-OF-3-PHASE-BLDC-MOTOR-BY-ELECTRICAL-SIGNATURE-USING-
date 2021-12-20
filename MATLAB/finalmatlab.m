clc; clear all; close all;
data = readtable('E:\hard\bearing_with_lubricant (6)'); %put file name here 
data=table2array(data);
subplot(2,1,1);
data_a= data(:,2);
data_b= data(:,3);
data_c= data(:,4);
L_a=size(data_a);
L_a=L_a(1,1);
Fs = 1000;            % Sampling frequency                    
T = 1/Fs;              % Sampling period       
t = (0:L_a-1)*T;     %vector lenghth
Y1 = fft(data_a); 
P2 = abs(Y1/L_a); %normalisation of signal
P1 = P2(1:L_a/2+1); 
f = Fs*(0:(L_a/2))/L_a;
% %if
%     P1(P1> 0.02)=1;
%     P1(P1< 0.02)=0;
% %     Y=1
% %else
% %     Y=0;
% %end ;

subplot(3,1,1);
plot(f,P1)
% xlim([0 50]);
% ylim([0.0004 2]);
title('Single-Sided Amplitude Spectrum of X(t)');
xlabel('f (Hz)');
ylabel('|P1(f)|');
yline(max(P1),'-', strcat('Max : ',num2str(max(P1))));
yline(min(P1),'-', strcat('Min : ',num2str(min(P1))));

L_a2=size(data_b);
L_a2=L_a2(1,1);
Fs = 1000;            % Sampling frequency                    
T = 1/Fs;              % Sampling period       
t = (0:L_a2-1)*T;     %vector lenghth
Y2 = fft(data_b); 
P4 = abs(Y2/L_a2); %normalisation of signal
P3 = P4(1:L_a2/2+1); 
f2 = Fs*(0:(L_a2/2))/L_a2;
% % if
%     P3(P3> 0.02)=1;
%     P3(P3< 0.02)=0;
% %     Y=1
% % else
% %     Y=0;
% % end ;
subplot(3,1,2);
plot(f2,P3)
% xlim([0 50]);
% ylim([0.0004 2]);
title('Single-Sided Amplitude Spectrum of X(t)');
xlabel('f (Hz)');
ylabel('|P3(f)|');
yline(max(P3),'-', strcat('Max : ',num2str(max(P3))));
yline(min(P3),'-', strcat('Min : ',num2str(min(P3))));

L_a3=size(data_c);
L_a3=L_a3(1,1);
Fs = 1000;            % Sampling frequency                    
T = 1/Fs;              % Sampling period       
t = (0:L_a3-1)*T;     %vector lenghth
Y3 = fft(data_c); 
P6 = abs(Y3/L_a3); %normalisation of signal
P5 = P6(1:L_a3/2+1); 
f3 = Fs*(0:(L_a3/2))/L_a3;
% %if
%     P5(P5> 0.02)=1;
%     P5(P5< 0.02)=0;
% %     Y=1
% %else
% %     Y=0;
% %end ;
subplot(3,1,3);
plot(f3,P5)
% xlim([0 50]);
% ylim([0.0004 2]);
title('Single-Sided Amplitude Spectrum of X(t)');
xlabel('f (Hz)');
ylabel('|P5(f)|');
yline(max(P5),'-', strcat('Max : ',num2str(max(P5))));
yline(min(P5),'-', strcat('Min : ',num2str(min(P5))));


