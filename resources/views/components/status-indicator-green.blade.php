<style>
  .status-indicator-green {
  width: 13px;
  height: 13px;
  border-radius: 50%;
  background-color: green;
  box-shadow: 0 0 0px green;
  animation: blink 1.5s infinite;
  margin-top: auto;
  margin-bottom: auto;
}

@keyframes blink {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}
</style>

<div class="status-indicator-green"></div>